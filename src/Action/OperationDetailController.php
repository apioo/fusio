<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Client;

class OperationDetailController extends ActionAbstract
{
    protected $db;

    public function __construct()
    {
        $this->db = $this->connector->getConnection('System');
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $operation = $this->db->fetchAssociative("SELECT danapay_app_operations.*, fusio_app.app_key, fusio_user.email, danapay_app_configurations.webhook, danapay_app_configurations.salt
            FROM danapay_app_operations INNER JOIN fusio_app ON danapay_app_operations.app_id = fusio_app.id 
            INNER JOIN fusio_user ON fusio_app.user_id = fusio_user.id
            LEFT JOIN danapay_app_configurations ON danapay_app_configurations.app_id = danapay_app_operations.app_id
            WHERE danapay_app_operations.`id` = :id AND danapay_app_operations.payment_id IS NULL", [
            'id' => $request->get('id')
        ]);

        if(!$operation) {
            return $this->response->build(403, [], [
                'message' => 'This one-time link has already been processed. Please generate a new one.'
            ]);
        }

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

        $payload = json_decode($operation['payload'], true);
        $payload['receiver'] = $operation['email'];
        $payload['payment_id'] = $operation['payment_id'];
        $payload['app_key'] = $operation['app_key'];

        return $this->response->build(200, [], $payload);
    }
}
