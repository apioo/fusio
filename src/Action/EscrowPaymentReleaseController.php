<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Model\UserInterface;
use GuzzleHttp\Client;

class EscrowPaymentReleaseController extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');
        $id = $request->get('payment_id');
        $operation = $db->executeQuery("SELECT * FROM danapay_app_operations WHERE id = :id AND app_id = :app_id", [
            'id' => $id,
            'app_id' => $app_id
        ])->fetchAssociative();
        if($operation) {
            return [
                'redirect_url' => getenv('DANAPAY_WEBAPP_URL').'/#/login?action=release&paiment_external_id='.$operation['payment_id'],
            ];
        }
        return $this->response->build(404, [], [
            'message' => 'Transaction not found or not authorized'
        ]);
    }
} 