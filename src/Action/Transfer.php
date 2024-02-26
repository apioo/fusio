<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use GuzzleHttp\Client;
use PSX\Framework\Util\Uuid;
use stdClass;

class Transfer
{
    protected $db;

    protected $app_id;

    protected $app_key;

    private $response;

    private $admin_token = false;

    private function getAdminToken() {
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

    public function __construct($connector, $app_id, $app_key, $app_owner_email, $response)
    {
        $this->app_id = $app_id;
        $this->app_key = $app_key;
        $this->app_owner_email = $app_owner_email;
	    $this->response = $response;
        $this->db = $connector->getConnection('System');
    }

    private function getUserToken($user_id, $app_id) {
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

    public function executeById($order_reference, $receiver_id, $amount, $payload) {
        $row = $this->db->executeQuery("SELECT * FROM danapay_app_users WHERE `app_id` = :app_id AND `id` = :id", [
            'app_id' => $this->app_id,
            'id' => $receiver_id
        ])->fetchAssociative();
        if(!$row) {
            return $this->response->build(404, [], [
                'message' => 'Recipient not found'
            ]);
        }

        $token = $this->getUserToken($row['user_id'], $this->app_id);
        $receiver = new stdClass();
        $receiver->email = $token->user->email;
        $receiver->first_name = $token->user->first_name;
        $receiver->company_name = $token->user->company ? $token->user->company->name : '';
        $receiver->last_name = $token->user->last_name;
        $receiver->phone_number = $token->user->phone_number;
        $cs = array_values(array_filter(Library::COUNTRIES, function($item)use($token){
            return $item['name'] == $token->user->country && $item['dial_code'] == $token->user->country_code;
        }));
        $receiver->country = $cs[0]['code'];
        return $this->execute($order_reference, $receiver, $amount, $payload);
    }

    public function execute($order_reference, $receiver, $amount, $payload) {
        //Put SEPA default payment method
        $payment_provider=isset($payload['_payment_provider'])?$payload['_payment_provider']:'SEPA';
        //the payment method is saved in the payload later
        $payload['_payment_provider'] = $payment_provider;

        $admin_token = $this->getAdminToken();
        $app_id = $this->app_id;

        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);

        //check if the user exists with such email, phone_number, country - using admin_token
        //get phone indicator for the specified country
        $res = $client->request('GET', '/api/v1/users/byEmail/' . $this->app_owner_email, [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $admin_token->access_token
                ]
        ]);
        $app_owner = json_decode($res->getBody());
        if(!$app_owner) {
            return $this->response->build(403, [], [
                'message' => 'This app cannot make payment anymore. Contact the administrator'
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

        //check sending country first
        $sender_country = null;
        $receiver_country = null;
        $cash_in_method = null;
        foreach($danapay_countries->data as $country) {
            if($country->country_code == $app_owner->country_code && $country->name == $app_owner->country) {
                //$country contains all data about cashin_methods and can check if the requested recipient can receive from the app owner
                $sender_country = $country;
                //check if recipient country is supported by sender country
                foreach($sender_country->receiving_countries as $receiving_country) {
                    if($receiving_country->receiving_country->code == $receiver->country) {
                        $receiver_country = $receiving_country->receiving_country;
                        break;
                    }
                }
                //use the first available cashin method as default
                $cash_in_method = array_values(array_filter($sender_country->cash_in_methods, function($item){
                    return $item->cash_in_method->payment_provider->name == 'danapay' && $item->cash_in_method->payment_type->name == 'balance';
                }))[0];
                break;
            }
        }
        if(!$receiver_country) {
            return $this->response->build(404, [], [
                'message' => 'Receiver country is invalid'
            ]);
        }
        if(!$sender_country) {
            return $this->response->build(404, [], [
                'message' => 'Sender country is invalid'
            ]);
        }
        
        //use the app owner's token to create his contact and initiate the transfer
        $app_owner_token = $this->getUserToken($app_owner->id, $app_id);

        //format phone number as expected by the backend API
        $clean_phone_number = $receiver->phone_number;
        if(!preg_match("/^\d+$/", $clean_phone_number) || strlen($clean_phone_number)>12) {
            return $this->response->build(422, [], [
                'message' => 'Phone number cannot contain more than 12 characters excluding the region prefix'
            ]);
        }

        //check if the recipient is already in our database
        $res = $client->request('GET', '/api/v1/customers/exists?country_code='.$receiver_country->country_code.'&phone_number='.$clean_phone_number.'&first_name='.$receiver->first_name.'&last_name='.$receiver->last_name.'&company_name='.$receiver->company_name, [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $admin_token->access_token
                ]
        ]);
        $customer = json_decode($res->getBody());

        //check if operation is already in our database
        $operation = $this->db->fetchAssociative("SELECT payment_id, id FROM danapay_app_operations WHERE `app_id` = :app_id AND `reference` = :reference AND JSON_EXTRACT(`payload`, '$.user.phone_number') = :phone_number AND JSON_EXTRACT(`payload`, '$.user.country') = :country", [
            'app_id' => $app_id,
            'reference' => $order_reference,
            'phone_number' => $clean_phone_number,
            'country' => $receiver->country
        ]);

        if(!$operation) {
            $id = Uuid::timeBased();
            //check if reference is already used
            $reference_unique = $this->db->fetchAssociative("SELECT payment_id, id FROM danapay_app_operations WHERE `app_id` = :app_id AND `reference` = :reference", [
                'app_id' => $app_id,
                'reference' => $order_reference
            ]);
            if($reference_unique) {
                return $this->response->build(422, [], [
                    'message' => 'Order reference already used'
                ]);
            }

            if(!$customer->exists) {
                //save the payload and redirect to onboarding, telling danapay to redirect to API management system once done
                try {                    
                    $this->db->executeStatement("INSERT INTO danapay_app_operations (`id`, `app_id`, `reference`, `payload`, `created_at`, `updated_at`) VALUES (:id, :app_id, :reference, :payload, NOW(), NOW()) ON DUPLICATE KEY UPDATE app_id = :app_id", [
                                            'id' => $id,
                                            'app_id' => $app_id,
                                            'reference' => $order_reference,
                                            'payload' => json_encode($payload)
                                        ]);
                }
                catch (\Exception $e) {
                    return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : [
                        'message' => 'Something went wrong'
                    ]);
                }
                //create the contact
                $res = $client->request('post', '/api/v1/auth/contacts', [
                    'json' => [
                        'email' => $receiver->email,
                        'first_name' => $receiver->first_name,
                        'last_name' => $receiver->last_name,
                        'phone_number' => $clean_phone_number,
                        'country_code' => $receiver_country->country_code
                    ],
                    'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => "Bearer " . $app_owner_token->access_token
                    ]
                ]);
                $ret = json_decode($res->getBody());
            }
            //initiate the payment and generate the redirect uri for payment 
            $res = $client->request('POST', '/api/v1/payments', [
                'json' => [
                        "cashin_method_id" => $cash_in_method->cash_in_method->id,
                        "amount_without_fees_in_euro" => $amount,
                        "payment_delivery" => false,
                        "is_escrowed" => false,
                        "sending_country_id" => $sender_country->id,
                        "receiving_country_id" => $receiver_country->id,
                        "phone_number" => $clean_phone_number,
                        "country_code" => $receiver_country->country_code,
                        "reason" => $payload['reason'],
                        "is_payment_on_demand" => false
                ],
                'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $app_owner_token->access_token
                ]
            ]);
            $ret = json_decode($res->getBody());
            $payment_id = $ret->details->id;
            if(!$payment_id && $ret->payment) {
                $payment_id = $ret->payment->id;
            }
            $this->db->executeStatement("INSERT INTO danapay_app_operations (`id`, `app_id`, `payment_id`, `reference`, `payload`, `created_at`, `updated_at`) VALUES (:id, :app_id, :payment_id, :reference, :payload, NOW(), NOW()) ON DUPLICATE KEY UPDATE payment_id = :payment_id", [
                'id' => $id,
                'app_id' => $app_id,
                'payment_id' => $payment_id,
                'reference' => $order_reference,
                'payload' => json_encode($payload)
            ]);
            return [
                'id' => $id
            ];
        }
        else {
            //check if it is resumable paymentid
            $res = $client->request('GET', '/api/v1/operations/' . $operation['payment_id'], [
                'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $app_owner_token->access_token
                ]
            ]);
            $ret = json_decode($res->getBody());
            if($ret->source_user->email != $this->app_owner_email) {
                return $this->response->build(422, [], [
                    'message' => 'Order duplicate'
                ]);
            }
            if($ret->status != 'undefined') {
                return $this->response->build(422, [], [
                    'message' => 'Order already processed'
                ]);
            }
            return [
                'id' => $operation['id']
            ];
        }
    }

    public function executeDirect($order_reference, $receiver, $amount, $payload) {
        if(isset($receiver->mfi) && !isset($receiver->phone_number)) {
            return $this->response->build(422, [], [
                'message' => 'Phone number is required to make direct transfer with microfinance institution'
            ]);
        }

        //Put SEPA default payment method
        $payment_provider=isset($payload['_payment_provider'])?$payload['_payment_provider']:'SEPA';
        //the payment method is saved in the payload later
        $payload['_payment_provider'] = $payment_provider;

        $admin_token = $this->getAdminToken();
        $app_id = $this->app_id;

        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);

        //check if the user exists with such email, phone_number, country - using admin_token
        //get phone indicator for the specified country
        $res = $client->request('GET', '/api/v1/users/byEmail/' . $this->app_owner_email, [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $admin_token->access_token
                ]
        ]);
        $app_owner = json_decode($res->getBody());
        if(!$app_owner) {
            return $this->response->build(403, [], [
                'message' => 'This app cannot make payment anymore. Contact the administrator'
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

        //check sending country first
        $sender_country = null;
        $receiver_country = null;
        $cash_in_method = null;
        foreach($danapay_countries->data as $country) {
            if($country->country_code == $app_owner->country_code && $country->name == $app_owner->country) {
                //$country contains all data about cashin_methods and can check if the requested recipient can receive from the app owner
                $sender_country = $country;
                //check if recipient country is supported by sender country
                foreach($sender_country->receiving_countries as $receiving_country) {
                    if($receiving_country->receiving_country->code == $receiver->country) {
                        $receiver_country = $receiving_country->receiving_country;
                        break;
                    }
                }
                //use the first available cashin method as default
                $cash_in_method = array_values(array_filter($sender_country->cash_in_methods, function($item){
                    return $item->cash_in_method->payment_provider->name == 'danapay' && $item->cash_in_method->payment_type->name == 'balance';
                }))[0];
                break;
            }
        }
        if(!$receiver_country) {
            return $this->response->build(404, [], [
                'message' => 'Receiver country is invalid'
            ]);
        }
        if(!$sender_country) {
            return $this->response->build(404, [], [
                'message' => 'Sender country is invalid'
            ]);
        }
        
        //use the app owner's token to create his contact and initiate the transfer
        $app_owner_token = $this->getUserToken($app_owner->id, $app_id);

        if(isset($receiver->phone_number)) {
            //format phone number as expected by the backend API
            $clean_phone_number = $receiver->phone_number;
            if(!preg_match("/^\d+$/", $clean_phone_number) || strlen($clean_phone_number)>12) {
                return $this->response->build(422, [], [
                    'message' => 'Phone number cannot contain more than 12 characters excluding the region prefix'
                ]);
            }
        }

        if(isset($receiver->phone_number) && isset($receiver->operator)) {
            //check if operation is already in our database
            $operation = $this->db->fetchAssociative("SELECT payment_id, id FROM danapay_app_operations WHERE `app_id` = :app_id AND `reference` = :reference AND JSON_EXTRACT(`payload`, '$.user.phone_number') = :phone_number AND JSON_EXTRACT(`payload`, '$.user.country') = :country", [
                'app_id' => $app_id,
                'reference' => $order_reference,
                'phone_number' => $clean_phone_number,
                'country' => $receiver->country
            ]);
        }
        elseif(isset($receiver->iban)) {
            //check if operation is already in our database
            $operation = $this->db->fetchAssociative("SELECT payment_id, id FROM danapay_app_operations WHERE `app_id` = :app_id AND `reference` = :reference AND JSON_EXTRACT(`payload`, '$.user.account_holder') = :account_holder AND JSON_EXTRACT(`payload`, '$.user.iban') = :iban AND JSON_EXTRACT(`payload`, '$.user.country') = :country", [
                'app_id' => $app_id,
                'reference' => $order_reference,
                'account_holder' => $receiver->account_holder,
                'iban' => $receiver->iban,
                'country' => $receiver->country
            ]);
        }

        if(!$operation) {
            $id = Uuid::timeBased();
            //check if reference is already used
            $reference_unique = $this->db->fetchAssociative("SELECT payment_id, id FROM danapay_app_operations WHERE `app_id` = :app_id AND `reference` = :reference", [
                'app_id' => $app_id,
                'reference' => $order_reference
            ]);
            if($reference_unique) {
                return $this->response->build(422, [], [
                    'message' => 'Order reference already used'
                ]);
            }

            //save the payload and redirect to onboarding, telling danapay to redirect to API management system once done
            try {                    
                $this->db->executeStatement("INSERT INTO danapay_app_operations (`id`, `app_id`, `reference`, `payload`, `created_at`, `updated_at`) VALUES (:id, :app_id, :reference, :payload, NOW(), NOW()) ON DUPLICATE KEY UPDATE app_id = :app_id", [
                                        'id' => $id,
                                        'app_id' => $app_id,
                                        'reference' => $order_reference,
                                        'payload' => json_encode($payload)
                                    ]);
            }
            catch (\Exception $e) {
                return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : [
                    'message' => 'Something went wrong'
                ]);
            }

            $direct_payload = [
                "cashin_method_id" => $cash_in_method->cash_in_method->id,
                "amount" => $amount,
                "currency" => "CFA",
                "payment_delivery" => false,
                "verify" => false,
                "is_escrowed" => false,
                "country_code" => $receiver_country->country_code,
                "reason" => $payload['reason'],
                "is_payment_on_demand" => false
            ];
            if(isset($receiver->phone_number) && isset($receiver->operator)) {
                $direct_payload["phone_number"] = $clean_phone_number;
                $direct_payload["operator"] = $receiver->operator;
            }
            elseif(isset($receiver->iban)) {
                $direct_payload["iban"] = $receiver->iban;
                $direct_payload["account_holder"] = $receiver->account_holder;
                $direct_payload["bank_name"] = $receiver->bank_name;
            }
            elseif(isset($receiver->mfi)) {
                $direct_payload["mfi_owner_id"] = $receiver->account_number;
                $direct_payload["mfi_account_name"] = $receiver->account_holder;
                $direct_payload["mfi_name"] = $receiver->mfi;
                $direct_payload["phone_number"] = $clean_phone_number;
            }
            
            //initiate the payment and generate the redirect uri for payment 
            $res = $client->request('POST', '/api/v1/payments/direct', [
                'json' => $direct_payload,
                'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $app_owner_token->access_token,
                    'X-APP-ID' => $this->app_id
                ]
            ]);
            $ret = json_decode($res->getBody());
            $payment_id = $ret->details->id;
            if(!$payment_id && $ret->payment) {
                $payment_id = $ret->payment->id;
            }
            $this->db->executeStatement("INSERT INTO danapay_app_operations (`id`, `app_id`, `payment_id`, `reference`, `payload`, `created_at`, `updated_at`) VALUES (:id, :app_id, :payment_id, :reference, :payload, NOW(), NOW()) ON DUPLICATE KEY UPDATE payment_id = :payment_id", [
                'id' => $id,
                'app_id' => $app_id,
                'payment_id' => $payment_id,
                'reference' => $order_reference,
                'payload' => json_encode($payload)
            ]);
            return [
                'id' => $id
            ];
        }
        else {
            //check if it is resumable paymentid
            $res = $client->request('GET', '/api/v1/operations/' . $operation['payment_id'], [
                'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $app_owner_token->access_token
                ]
            ]);
            $ret = json_decode($res->getBody());
            if($ret->source_user->email != $this->app_owner_email) {
                return $this->response->build(422, [], [
                    'message' => 'Order duplicate'
                ]);
            }
            if($ret->status != 'undefined') {
                return $this->response->build(422, [], [
                    'message' => 'Order already processed'
                ]);
            }
            return [
                'id' => $operation['id']
            ];
        }
    }
}
