<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Model\UserInterface;
use GuzzleHttp\Client;
use PSX\Framework\Util\Uuid;

class EscrowPaymentController extends DanapayController
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $admin_token = $this->getAdminToken();
        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);
        $res = $client->request('GET', '/api/v1/transactions/transferCountries', [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $admin_token->access_token
                ]
        ]);
        $danapay_countries = json_decode($res->getBody());
        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');
        $row = $db->executeQuery("SELECT user_id FROM danapay_app_users WHERE `app_id` = :app_id AND `id` = :id", [
            'app_id' => $app_id,
            'id' => $request->get("sender_id")
        ])->fetchAssociative();
        $token = $this->getUserToken($row['user_id'], $app_id);
        foreach(Library::COUNTRIES as $s_country) {
            if($s_country['dial_code'] == $token->user->country_code) {
                $sender_countries = array_filter($danapay_countries->data, function($item)use($s_country){
                    return $item->country_code == $s_country['dial_code'];
                });
                if(count($sender_countries)>0) {
                    $sender_country = array_values($sender_countries)[0];
                }
                break;
            } 
        }
        if(!$sender_country) {
            return $this->response->build(404, [], [
                'message' => 'Sender country is invalid'
            ]);
        }
        foreach(Library::COUNTRIES as $country) {
            if($country['code'] == $request->get("recipient")->country_iso_code) {
                $recipient_countries = array_filter($sender_country->receiving_countries, function($item)use($country){
                    return $item->receiving_country->country_code == $country['dial_code'];
                });
                if(count($recipient_countries)>0) {
                    $recipient_country = array_values($recipient_countries)[0];
                }
                break;
            } 
        }
        if(!$recipient_country) {
            return $this->response->build(404, [], [
                'message' => 'Recipient country is invalid'
            ]);
        }
        $res = $client->request('POST', '/api/v1/users/getBeneficiary', [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $token->access_token
                ]
            ,
            'json' => [
                'phone_number' => $request->get("recipient")->phone_number,
                'country_code' => $country['dial_code'],
                "email" => $request->get("recipient")->email
            ]
        ]);
        $ret = json_decode($res->getBody());
        $fee_value = 0;
        foreach($sender_country->fees as $fee) {
            if($fee->min<=$request->get("amount") && $request->get("amount")<=$fee->max) {
                $fee_value = $fee->value;
            }
        }
        foreach($sender_country->cash_in_methods as $cash_in_method) {
            if($cash_in_method->cash_in_method->payment_type->name=='bank_transfer')
                break;
        }
        $res = $client->request('POST', '/api/v1/payments/fintecture/initiatePayment', [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $token->access_token
                ]
            ,
            'json' => [
                'cashin_method_id' => $cash_in_method->cash_in_method->id,
                'amount_in_euros' => $request->get("amount"),
                'fee' => $fee_value,
                'payment_delivery' => false,
                'is_escrowed' => true,
                'sending_country_id' => $sender_country->id,
                'receiving_country_id' => $recipient_country->receiving_country->id,
                'phone_number' => $request->get("recipient")->phone_number,
                'country_code' => $country['dial_code'],
                'app_id' => $app_id,
                'client_name' => getenv('CLIENT_NAME'),
                'client_secret' => getenv('CLIENT_SECRET')
            ]
        ]);
        $ret = json_decode($res->getBody());
        $id = Uuid::timeBased();
        $db->executeStatement("INSERT INTO danapay_app_operations (`id`, `app_id`, `payment_id`, `created_at`, `updated_at`) VALUES (:id, :app_id, :payment_id, NOW(), NOW())", [
            'id' => $id,
            'app_id' => $app_id,
            'payment_id' => $ret->payment->id
        ]);
        return [
            'amount_received' => 656.957*doubleval($ret->payment->amount), //@todo is it really a constant
            'fees' => $fee_value,
            'sender_id' => $request->get("sender_id"),
            'recipient' => [
                'email' => $request->get("recipient")->email,
                'first_name' => $request->get("recipient")->first_name,
                'company_name' => $request->get("recipient")->company_name,
                'last_name' => $request->get("recipient")->last_name,
                'phone_number' => $request->get("recipient")->phone_number,
                'country_iso_code' => $request->get("recipient")->country_iso_code,
            ],
            'amount' => $ret->payment->amount,
            'redirect_url' => getenv('DANAPAY_WEBAPP_URL').'/#/login?paiment_external_id='.$ret->payment->id,
            'payment_id' => $id,
        ];
    }
} 