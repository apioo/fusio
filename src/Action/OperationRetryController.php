<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Model\UserInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OperationRetryController extends ActionAbstract
{
    protected $db;

    public function __construct()
    {
        $this->db = $this->connector->getConnection('System');
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $app_id = $request->get('app_id');
        $operation = $this->db->fetchAssociative("SELECT danapay_app_operations.*, fusio_app.app_key, fusio_user.email, danapay_app_configurations.webhook, danapay_app_configurations.salt
            FROM danapay_app_operations INNER JOIN fusio_app ON danapay_app_operations.app_id = fusio_app.id 
            INNER JOIN fusio_user ON fusio_app.user_id = fusio_user.id
            INNER JOIN danapay_app_configurations ON danapay_app_configurations.app_id = danapay_app_operations.app_id
            WHERE fusio_app.app_key = :app_id AND danapay_app_operations.`id` = :id", [
            'app_id' => $app_id,
            'id' => $request->get('id')
        ]);

        $npayload = json_decode($operation['payload'], true);

        $status = [
            'at' => date('c'),
            'status' => 'payment_initiation',
            'order_reference' => $npayload['order_reference']
        ];

        if($operation['webhook']) {
            $salt = $operation['salt'];
            $log_response = '';
            $client = new Client();
            try {
                $response = $client->request('POST', $operation['webhook'], [
                    'json' => $status,
                    'headers' => [
                        'X-Signature-DP' => Library::sign($status, $salt)
                    ]
                ]);
                $log_response = $response->getBody();
            }
            catch(\Exception $e) {
                $log_response = $e->getMessage();
            }
            $now = new \DateTime();
            $this->db->insert('danapay_callbacks', array(
                'app_id' => $operation['app_id'],
                'url' => $operation['webhook'],
                'request' => json_encode($status),
                'response' => $log_response,
                'insert_date' => $now->format('Y-m-d H:i:s')
            ));
        }

        $_operation = new Payment($this->connector, $operation['app_id'], $operation['app_key'], $operation['email'], $this->response);

        $payload = json_decode($operation['payload']);

        try {
            return $_operation->execute($payload->order_reference, $payload->user, $payload->amount, json_decode($operation['payload'], true));
        }
        catch(RequestException $e) {
            return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Remote server failed returning full response.');
        }
    }
}
