<?php
namespace App\Action;

use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PSX\Framework\Util\Uuid;
use Doctrine\DBAL\DriverManager;

class WithdrawController extends DanapayController
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');
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
            $res = $client->request('POST', '/api/v1/initiatePayout', [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => "Bearer " . $token->access_token
                    ],
                'json' => [
                    "amount_without_fees_in_euro" => $request->get('amount'),
                    "cashout_method_id"=> $cash_out_method->cashout_method->id,
                    "bank_account_id"=> $request->get('bank_id'),
                    "mobile_money_account_id"=> null,
                    "local_amount"=> $request->get('amount'),
                    "local_currency"=> "EUR",
                    "country_id"=> $app_owner->country_id
                ]
            ]);
            $ret = json_decode($res->getBody());
            $cashout_id = $ret->cashout->id;
            //save the payload and redirect to onboarding, telling danapay to redirect to API management system once done
            $id = Uuid::timeBased();
            try {          
                $db->executeStatement("INSERT INTO danapay_app_operations (`id`, `app_id`, `payload`, `created_at`, `updated_at`) VALUES (:id, :app_id, :payload, NOW(), NOW()) ON DUPLICATE KEY UPDATE app_id = :app_id", [
                                        'id' => $id,
                                        'app_id' => $app_id,
                                        'payload' => json_encode($ret)
                                    ]);
            }
            catch (\Exception $e) {
                return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Something went wrong');
            }
            /** 
             * Send OTP by email
             */
            $res = $client->request('POST', '/api/v1/cashout/'.$cashout_id.'/resendCode', [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => "Bearer " . $token->access_token
                    ],
                'json' => [
                    "channel" => "mail"
                ]
            ]);
            $ret = json_decode($res->getBody());
            return [
                'id' => $id,
                'message' => $ret->message
            ];
        }
        catch(RequestException $e) {
            return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Something went wrong.');
        }
    }
} 
