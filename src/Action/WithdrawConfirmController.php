<?php
namespace App\Action;

use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PSX\Framework\Util\Uuid;
use Doctrine\DBAL\DriverManager;

class WithdrawConfirmController extends DanapayController
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');  
        //check if operation is already in our database
        $operation = $db->fetchAssociative("SELECT payment_id, payload FROM danapay_app_operations WHERE `app_id` = :app_id AND `id` = :id", [
            'app_id' => $app_id,
            'id' => $request->get('id')
        ]);
        if(!$operation) {
            return $this->response->build(404, [], [
                'message' => 'Cashout not found.'
            ]);
        }

        $npayload = json_decode($operation['payload'], true);

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
            return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : [
                'message' => 'Could not authenticate user.'
            ]);
        }
        if(!$app_owner) {
            return $this->response->build(403, [], [
                'message' => 'This app has been disabled. Contact the administrator'
            ]);
        }
        //get danapay countries with configured payin method and receiving country if current transfer is allowed between both
        $res = $client->request('GET', '/api/v1/transactions/transferCountries', [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $admin_token->access_token
                ]
        ]);
        $danapay_countries = json_decode($res->getBody());
        //find available cashout method
        foreach($danapay_countries->data as $country) {
            if($country->country_code == $app_owner->country_code && $country->name == $app_owner->country) {
                //use the first available cashin method as default
                $cash_out_method = array_values(array_filter($country->cashout_methods, function($item){
                    return $item->cashout_method->payment_provider->name == 'danapay' && $item->cashout_method->payment_type->name == 'manual_bank_transfer';
                }))[0];
                break;
            }
        }
        $token = $this->getUserToken($app_owner->id, $app_id);
        try {
            $res = $client->request('POST', '/api/v1/verifyPayout', [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => "Bearer " . $token->access_token
                    ],
                'json' => [
                    "key_code" => $request->get('otp'),
                    "id" => $npayload['cashout']['id']
                ]
            ]);
            $ret = json_decode($res->getBody());
            $db->executeStatement("UPDATE danapay_app_operations SET `payment_id` = :payment_id, updated_at = NOW() WHERE id = :id", [
                'id' => $request->get('id'),
                'payment_id' => $ret->cashout->payout->id
            ]);
            return [
                'id' => $request->get('id'),
                'message' => $ret->message
            ];
        }
        catch(RequestException $e) {
            return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : [
                'message' => 'Something went wrong. The code may have already expired. Please initiate another payout.'
            ]);
        }
    }
} 
