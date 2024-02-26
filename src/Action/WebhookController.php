<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Client;
use PSX\Framework\Util\Uuid;

class WebhookController extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $db = $this->connector->getConnection('System');

        $status = [
            'at' => $request->get('at'),
            'status' => $request->get("status"),
        ];

        $transaction = false;

        if(isset($request->get('payload')->payment_id)) {
            //get the app destination of the call
            $transaction = $db->executeQuery("SELECT id, app_id, payload FROM danapay_app_operations WHERE payment_id = :payment_id", [
                'payment_id' => $request->get('payload')->payment_id
            ])->fetchAssociative();
        }
        elseif(isset($request->get('payload')->email)) {
            $transaction = $db->executeQuery("SELECT id, app_id, payload FROM danapay_app_operations WHERE payload->'$.user.email' = :email 
            AND payload->'$.user.last_name' = :last_name
            AND payload->'$.user.first_name' = :first_name
            AND payload->'$.user.phone_number' = :phone_number", [
                'email' => $request->get('payload')->email,
                'last_name' => $request->get('payload')->last_name,
                'first_name' => $request->get('payload')->first_name,
                'phone_number' => $request->get('payload')->phone_number
            ])->fetchAssociative();
        }

        if(!$transaction) {
            return $this->response->build(404, [], [
                'response' => 'Transaction not in the AMS',
                'ee' => $request->get('payload')->payment_id
            ]);
        }

        $status['operation_id'] = $transaction['id'];
        $app_id = $transaction['app_id'];

        if($status['status']==='user_account_active') {
            $row = $db->executeQuery("SELECT id FROM danapay_app_users WHERE app_id = :app_id AND user_id = :user_id", [
                'app_id' => $app_id,
                'user_id' => $request->get('payload')->id
            ])->fetchAssociative();
            if(!$row) {
                $db->executeQuery("INSERT INTO danapay_app_users (id, app_id, user_id, external_user_id, created_at) VALUES (:id, :app_id, :user_id, :external_user_id, NOW()) ON DUPLICATE KEY UPDATE external_user_id = :external_user_id", [
                    'id' => Uuid::timeBased(),
                    'app_id' => $app_id,
                    'user_id' => $request->get('payload')->id,
                    'external_user_id' => $request->get('payload')->external_user_id
                ]);
            }
        }

        $npayload = json_decode($transaction['payload'], true);
        $status["order_reference"] = $npayload['order_reference'];

        //invoke the webhook registered in the fusio db
        $row = $db->executeQuery("SELECT `description`, `redirection_url`, `webhook`, `salt` FROM danapay_app_configurations WHERE app_id = ?", [$app_id])->fetchAssociative();

        if(!$row) {
            return $this->response->build(404, [], [
                'response' => 'App not configured'
            ]);  
        }

        if(!$row['webhook']) {
            return $this->response->build(404, [], [
                'response' => 'Webhook not implemented'
            ]);  
        }

        $salt = $row['salt'];
        $log_response = '';
        $client = new Client();
        try {
            $res = $client->request('POST', $row['webhook'], [
                'json' => $status,
                'headers' => [
                    'X-Signature-DP' => Library::sign($status, $salt)
                ]
            ]);
            $log_response = $res->getBody();
        }
        catch(\Exception $e) {
            $log_response = $e->getMessage();
        }
        $now = new \DateTime();
        $db->insert('danapay_callbacks', array(
            'app_id' => $app_id,
            'url' => $row['webhook'],
            'request' => json_encode($status),
            'response' => $log_response,
            'insert_date' => $now->format('Y-m-d H:i:s')
        ));

        return $this->response->build(200, [], [
            'status' => 'called',
            'response' => json_decode($res->getBody())
        ]);
    }
}