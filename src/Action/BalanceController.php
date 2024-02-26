<?php
namespace App\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Doctrine\DBAL\DriverManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BalanceController extends DanapayController
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $app_id = $context->getApp()->getId();
        $admin_token = $this->getAdminToken();
        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);
        try {
            $res = $client->request('GET', '/api/v1/users/byEmail/' . $context->getUser()->getEmail(), [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => "Bearer " . $admin_token->access_token
                    ]
            ]);
            $app_owner = json_decode($res->getBody());
        }
        catch(RequestException $e) {
            return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Could not authenticate user.'); 
        }
        if(!$app_owner) {
            return $this->response->build(403, [], [
                'message' => 'This app has been disabled. Contact the administrator'
            ]);
        }
        $token = $this->getUserToken($app_owner->id, $app_id);
        $res = $client->request('GET', '/api/v1/auth/current', [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $token->access_token
                ]
        ]);
        $ret = json_decode($res->getBody());
        return [
            [
                'currency' => 'EUR',
                'amount' => doubleval($ret->client->euro_balance),
                'available' => doubleval($ret->client->euro_balance)
            ]
        ];
    }
} 
