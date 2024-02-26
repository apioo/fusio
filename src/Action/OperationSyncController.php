<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Client;

class OperationSyncController extends ActionAbstract
{
    protected $db;

    public function __construct()
    {
        $this->db = $this->connector->getConnection('System');
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $this->db->executeStatement("UPDATE danapay_app_operations SET `payment_id` = :payment_id, updated_at = NOW() WHERE id = :id", [
            'id' => $request->get('id'),
            'payment_id' => $request->get('payment_id')
        ]);
        $operation = $this->db->fetchAssociative("SELECT danapay_app_operations.*, fusio_app.app_key, fusio_user.email, danapay_app_configurations.webhook, danapay_app_configurations.salt
            FROM danapay_app_operations INNER JOIN fusio_app ON danapay_app_operations.app_id = fusio_app.id 
            INNER JOIN fusio_user ON fusio_app.user_id = fusio_user.id
            INNER JOIN danapay_app_configurations ON danapay_app_configurations.app_id = danapay_app_operations.app_id
            WHERE danapay_app_operations.`id` = :id", [
            'id' => $request->get('id')
        ]);

        $npayload = json_decode($operation['payload'], true);

        $status = [
            'at' => date('c'),
            'status' => 'payment_started',
            'order_reference' => $npayload['order_reference']
        ];

        if($operation['webhook']) {
            $salt = $operation['salt'];
            $response_log = '';
            $client = new Client();
            try {
                $response = $client->request('POST', $operation['webhook'], [
                    'json' => $status,
                    'headers' => [
                        'X-Signature-DP' => Library::sign($status, $salt)
                    ]
                ]);
                $response_log = $response->getBody();
            }
            catch(\Exception $e) {
                //fail silently if webhook is not responding
                $response_log = $e->getMessage();
            }

            $now = new \DateTime();
            $this->db->insert('danapay_callbacks', array(
                'app_id' => $operation['app_id'],
                'url' => $operation['webhook'],
                'request' => json_encode($status),
                'response' => $response_log,
                'insert_date' => $now->format('Y-m-d H:i:s')
            ));
        }

        $payload = json_decode($operation['payload'], true);

        return $this->response->build(200, [], $payload);
    }
}
