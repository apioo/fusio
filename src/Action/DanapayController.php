<?php
namespace App\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Client;

class DanapayController extends ActionAbstract
{
    private $admin_token = false;

    protected function getAdminToken() {
        if(!$this->admin_token) {
            $client = new Client([
                'base_uri' => getenv('DANAPAY_API_ENDPOINT')
            ]);
            $res = $client->request('POST', '/api/v1/auth/byEmail', [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET')
                    ]
                ,
                'json' => [
                    'email' => getenv('DANAPAY_ADMIN_LOGIN'),
                    'password' => getenv('DANAPAY_ADMIN_PASSWORD')
                ]
            ]);
            $this->admin_token = json_decode($res->getBody());
        }
        return $this->admin_token;
    }

    protected function getUserToken($user_id, $app_id) {
        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);
        $token = $this->getAdminToken();
        $res = $client->request('POST', '/api/v1/auth/byApp', [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $token->access_token
                ]
            ,
            'json' => [
                'id' => $user_id
            ]
        ]);
        return json_decode($res->getBody());
    }

    protected function getManagerToken() {
        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);
        $res = $client->request('POST', '/api/v1/auth/byEmail', [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET')
                ]
            ,
            'json' => [
                'email' => getenv('DANAPAY_USER_LOGIN'),
                'password' => getenv('DANAPAY_USER_PASSWORD')
            ]
        ]);
        return json_decode($res->getBody());
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        
    }
} 
