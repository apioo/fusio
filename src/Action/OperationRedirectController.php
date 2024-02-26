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

class OperationRedirectController extends ActionAbstract
{
    protected $db;

    public function __construct()
    {
        $this->db = $this->connector->getConnection('System');
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $operation = $this->db->executeQuery("SELECT danapay_app_operations.id, payload, JSON_UNQUOTE(JSON_EXTRACT(payload, '$.redirect_base')) AS redirect_base FROM danapay_app_operations
		INNER JOIN fusio_app ON danapay_app_operations.app_id = fusio_app.id WHERE fusio_app.`app_key` = :app_key AND `payment_id` = :payment_id", [
            'app_key' => $request->get('app_id'),
            'payment_id' => $request->get('payment_id')
        ])->fetchAssociative();

        $row = $this->db->executeQuery("SELECT `redirection_url`, `webhook`, `salt`, `app_id` FROM danapay_app_configurations INNER JOIN fusio_app ON danapay_app_configurations.app_id = fusio_app.id WHERE fusio_app.app_key = ?", [$request->get('app_id')])->fetchAssociative();
        if(!$operation) {
            $has_qs = preg_match("/\?/", $row['redirection_url']);
            header('Location: ' . $row['redirection_url'] . (($has_qs ? '&' : '?') . 'status=' . $request->get('status')));
        }

        $npayload = json_decode($operation['payload'], true);

        try {
            $salt = $row['salt'];
            $log_response = '';
            $client = new Client();
            $status = [
                'status' => 'user_redirected_back',
                'order_reference' => $npayload['order_reference'],
                'at' => date('c')
            ];
            $response = $client->request('POST', $row['webhook'], [
                'json' => $status,
                'headers' => [
                    'X-Signature-DP' => Library::sign($status, $salt)
                ]
            ]);
            $log_response = $response->getBody();
        }
        catch(RequestException $e) {
            $log_response = $e->getMessage();
        }

        $now = new \DateTime();
        $this->db->insert('danapay_callbacks', array(
            'app_id' => $row['app_id'],
            'url' => $row['webhook'],
            'request' => json_encode($status),
            'response' => $log_response,
            'insert_date' => $now->format('Y-m-d H:i:s')
        ));

        if($operation) {
            $has_qs = preg_match("/\?/", $operation['redirect_base']);
            header('Location: ' . $operation['redirect_base'] . (($has_qs ? '&' : '?') . 'status=' . $request->get('status')));
            exit;
        }
        
        exit;
    }
}
