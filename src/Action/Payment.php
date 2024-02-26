<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use GuzzleHttp\Client;
use PSX\Framework\Util\Uuid;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class Payment
{
    protected $db;

    protected $app_id;

    protected $app_key;

    private $response;

    private $app_owner_email;

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

    private function getManagerToken() {
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

    public function anonymousLink($order_reference, $amount, $payload) {
        if(isset($payload['_payment_provider'])) {
            $payment_provider = $payload['_payment_provider'];
        }
        else {
            $payment_provider = 'SEPA';
        }
        $payload['_payment_provider'] = $payment_provider;
        $app_id = $this->app_id;
        $operation = $this->db->fetchAssociative("SELECT payment_id, id, payload, TIMESTAMPDIFF(HOUR, created_at, NOW()) AS past_hours FROM danapay_app_operations WHERE `app_id` = :app_id AND `reference` = :reference", [
            'app_id' => $app_id,
            'reference' => $order_reference
        ]);
        if(!$operation) {
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
            
            $id = Uuid::timeBased();

            $payload['validity_hours'] = 48;

            $this->db->executeStatement("INSERT INTO danapay_app_operations (`id`, `app_id`, `reference`, `payload`, `created_at`, `updated_at`) VALUES (:id, :app_id, :reference, :payload, NOW(), NOW())", [
                'id' => $id,
                'app_id' => $app_id,
                'reference' => $order_reference,
                'payload' => json_encode($payload)
            ]);
            $redirect_uri = getenv('DANAPAY_WEBAPP_URL').'/?appref='.$id;
            return [
                'hint' => 'This link will expire in ' . $payload['validity_hours'] . ' hours.',
                'status' => 'anonymous_link_generated',
                'redirect_uri' => $redirect_uri
            ];
        }
        else {
            $redirect_uri = getenv('DANAPAY_WEBAPP_URL').'/?appref='.$operation['id'];
            $data = json_decode($operation['payload'], true);
            return [
                'hint' => 'This link will expire in ' . ($data['validity_hours'] - $operation['past_hours']) . ' hours.',
                'status' => 'anonymous_link_generated',
                'redirect_uri' => $redirect_uri
            ];
        }
    }

    public function execute($order_reference, $sender, $amount, $payload) {
        $select_payment = true;
        if(isset($payload['_payment_provider'])) {
            $payment_provider = $payload['_payment_provider'];
        }
        else {
            $payment_provider = 'SEPA';
        }
        if(isset($payload['select_payment'])) {
            $select_payment = $payload['select_payment'];
        }
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
        $res = $client->request('GET', '/api/v1/transactions/transferCountries', [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $admin_token->access_token
                ]
        ]);
        $danapay_countries = json_decode($res->getBody());
        $receiving_country = null;
        $sender_country = null;
        foreach(Library::COUNTRIES as $s_country) {
            if($s_country['code'] == $sender->country) {
                $sender_countries = array_filter($danapay_countries->data, function($item)use($s_country, $app_owner, &$receiving_country){
                    $can_receive = [];
                    if($item->code == $s_country['code'] && $item->is_sending_country) {
                        $can_receive = array_filter($item->receiving_countries, function($_receiving_country)use($app_owner, &$receiving_country){
                            $ret = $_receiving_country->receiving_country->country_code == $app_owner->country_code;
                            if($ret) {
                                $receiving_country = $_receiving_country;
                            }
                            return $ret;
                        });
                    }
                    return count($can_receive)>0;
                });
                if(count($sender_countries)>0) {
                    $sender_country = array_values($sender_countries)[0];
                    break;
                }
            } 
        }
        if(!$sender_country) {
            return $this->response->build(404, [], [
                'message' => 'Sender country is invalid'
            ]);
        }
        $app_owner_token = $this->getUserToken($app_owner->id, $app_id);
        $clean_phone_number = $sender->phone_number;
        if(!preg_match("/^\d+$/", $clean_phone_number) || strlen($clean_phone_number)>12) {
            return $this->response->build(422, [], [
                'message' => 'Phone number cannot contain more than 12 characters excluding the region prefix'
            ]);
        }
        $res = $client->request('GET', '/api/v1/customers/exists?country_code='.$sender_country->country_code.'&phone_number='.$clean_phone_number.'&email='.$sender->email.'&first_name='.$sender->first_name.'&last_name='.$sender->last_name.'&company_name='.$sender->company_name, [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $admin_token->access_token
                ]
        ]);
        $customer = json_decode($res->getBody());
        $match_payment_method = false;
        if(isset($payload['payment_method'])) {
            foreach($sender_country->cash_in_methods as $cash_in_method) {
                if($cash_in_method->cash_in_method->name==$payload['payment_method']) {
                    $match_payment_method = true;
                    break;
                }  
            }
            if(!$match_payment_method) {
                return $this->response->build(422, [], [
                    'message' => 'Payment method is not available'
                ]);
            }
        }
        else {
            foreach($sender_country->cash_in_methods as $cash_in_method) {
                if($cash_in_method->cash_in_method->payment_provider->name==$payment_provider)
                    break;
            }
        }
        $operation = $this->db->fetchAssociative("SELECT payment_id, id FROM danapay_app_operations WHERE `app_id` = :app_id AND `reference` = :reference AND JSON_EXTRACT(`payload`, '$.user.phone_number') = :phone_number AND JSON_EXTRACT(`payload`, '$.user.country') = :country", [
            'app_id' => $app_id,
            'reference' => $order_reference,
            'phone_number' => $clean_phone_number,
            'country' => $sender->country
        ]);
        if(!$operation) {
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

            if($customer->exists) {
                //initiate the payment and generate the redirect uri for payment 
                $id = Uuid::timeBased();
                $res = $client->request('POST', '/api/v1/payments', [
                    'json' => [
                        'cashin_method_id' => $cash_in_method->cash_in_method->id,
                        'select_payment' => $select_payment,
                        'amount_without_fees_in_euro' => $amount,
                        'payment_delivery' => false,
                        'recipient_first_name' => $app_owner->first_name,
                        'recipient_last_name' => $app_owner->last_name,
                        'phone_number' => $clean_phone_number,
                        'country_code' => $sender_country->country_code,
                        'sending_country_id' => $sender_country->id,
                        'receiving_country_id' => $receiving_country->receiving_country->id,
                        'destination_city' => NULL,
                        'destination_quarter' => NULL,
                        'is_escrowed' => false,
                        'reason' => $payload['reason'],
                        'return_link' => true,
                        'cancel_url' => getenv('FUSIO_URL') . '/operations/'.$this->app_key.'/'.$id.'/cancel',
                        'redirect_url' => getenv('FUSIO_URL').'/redirect/'.$this->app_key.'/' // http://fusio.danapay.wr/redirect/[&payment_id=ID]
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
                if(!empty($customer->redirect_url)) {
                    $this->db->executeStatement("INSERT INTO danapay_app_operations (`id`, `app_id`, `reference`, `payload`, `created_at`, `updated_at`) VALUES (:id, :app_id, :reference, :payload, NOW(), NOW())", [
                        'id' => $id,
                        'app_id' => $app_id,
                        'reference' => $order_reference,
                        'payload' => json_encode($payload)
                    ]);
                    $redirect_uri = $customer->redirect_url.'&redirect_uri='.urlencode(getenv('FUSIO_URL').'/operations/'.$this->app_key.'/'.$id.'/retry');
                }
                else {
                    $this->db->executeStatement("INSERT INTO danapay_app_operations (`id`, `app_id`, `payment_id`, `reference`, `payload`, `created_at`, `updated_at`) VALUES (:id, :app_id, :payment_id, :reference, :payload, NOW(), NOW()) ON DUPLICATE KEY UPDATE payment_id = :payment_id", [
                        'id' => $id,
                        'app_id' => $app_id,
                        'payment_id' => $payment_id,
                        'reference' => $order_reference,
                        'payload' => json_encode($payload)
                    ]);
                    $redirect_uri = getenv('DANAPAY_WEBAPP_URL').'/payment/'.$payment_id;
                }
                if($payment_provider=='Hub2') {
                    if(count($ret->select_providers)>1) {
                        return [
                            'redirect_uri' => $redirect_uri
                        ];
                    }
                    $sender_token = $this->getUserToken($ret->details->user_id, $app_id);
                    $res = $client->request('POST', '/api/v1/payments/hub2/execute', [
                        'json' => [
                            'selected_provider' => strtolower($ret->select_providers[0]->name),
                            'payment_id' => $ret->details->id
                        ],
                        'headers' => 
                        [
                            'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                            'Authorization' => "Bearer " . $sender_token->access_token
                        ]
                    ]);
                    $ret = json_decode($res->getBody());
                    if($ret->nextAction->type==='otp') {
                        return [
                            'redirect_uri' => $redirect_uri
                        ];
                    }
                    return [
                        'status' => $ret->status,
                        'mode' => $ret->mode
                    ];
                }
                else {
                    return [
                        'redirect_uri' => $redirect_uri
                    ];
                }
            }
            else {
                //save the payload and redirect to onboarding, telling danapay to redirect to API management system once done
                $id = Uuid::timeBased();
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
                $res = $client->request('post', '/api/v1/auth/contacts', [
                    'json' => [
                        'email' => $sender->email,
                        'first_name' => $sender->first_name,
                        'last_name' => $sender->last_name,
                        'phone_number' => $clean_phone_number,
                        'country_code' => $sender_country->country_code,
                        'is_individual' => true
                    ],
                    'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => "Bearer " . $app_owner_token->access_token,
                        'X-APP-ID' => $this->app_id
                    ]
                ]);
                $ret = json_decode($res->getBody());
                /**
                 * Mail activation link, with message check your mailbox to get the temporary password
                 * Once password modified, he will be headed to fill his info and sumsub verification
                 * After sumsub verification, when he click retour, he will be redirected to {fusio}/retry/{id}
                 */
                return [
                    'hint' => 'This link will expire in 60 minutes.',
                    'status' => 'register_user',
                    'redirect_uri' => $ret->redirect_url.'&redirect_uri='.urlencode(getenv('FUSIO_URL').'/operations/'.$this->app_key.'/'.$id.'/retry')
                ];
            }
        }
        else {
            if($operation['payment_id']) {
                return [
                    'redirect_uri' => getenv('DANAPAY_WEBAPP_URL').'/payment/'.$operation['payment_id']
                ];
            }
            else {
                if($customer->exists) {
                    //initiate the payment and generate the redirect uri for payment 
                    $res = $client->request('POST', '/api/v1/payments', [
                        'json' => [
                            'cashin_method_id' => $cash_in_method->cash_in_method->id,
                            'select_payment' => $select_payment,
                            'amount_without_fees_in_euro' => $amount,
                            'payment_delivery' => false,
                            'recipient_first_name' => $app_owner->first_name,
                            'recipient_last_name' => $app_owner->last_name,
                            'phone_number' => $clean_phone_number,
                            'country_code' => $sender_country->country_code,
                            'sending_country_id' => $sender_country->id,
                            'receiving_country_id' => $receiving_country->receiving_country->id,
                            'destination_city' => NULL,
                            'destination_quarter' => NULL,
                            'is_escrowed' => false,
                            'reason' => $payload['reason'],
                            'return_link' => true,
                            'cancel_url' => getenv('FUSIO_URL') . '/operations/'.$this->app_key.'/'.$operation['id'].'/cancel',
                            'redirect_url' => getenv('FUSIO_URL').'/redirect/'.$this->app_key.'/' // http://fusio.danapay.wr/redirect/[&payment_id=ID]
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
                    $redirect_uri = getenv('DANAPAY_WEBAPP_URL').'/payment/'.$payment_id;
                    if(!empty($customer->redirect_url)) {
                        $redirect_uri = $customer->redirect_url.'&redirect_uri='.urlencode(getenv('FUSIO_URL').'/operations/'.$this->app_key.'/'.$operation['id'].'/retry');
                    }
                    $this->db->executeStatement("UPDATE danapay_app_operations SET `payment_id` = :payment_id, updated_at = NOW() WHERE id = :id", [
                        'id' => $operation['id'],
                        'payment_id' => $payment_id
                    ]);
                    if($payment_provider=='Hub2') {
                        if(count($ret->select_providers)>1) {
                            return [
                                'redirect_uri' => $redirect_uri
                            ];
                        }
                        $sender_token = $this->getUserToken($ret->details->user_id, $app_id);
                        $res = $client->request('POST', '/api/v1/payments/hub2/execute', [
                            'json' => [
                                'selected_provider' => strtolower($ret->select_providers[0]->name),
                                'payment_id' => $ret->details->id
                            ],
                            'headers' => 
                            [
                                'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                                'Authorization' => "Bearer " . $sender_token->access_token
                            ]
                        ]);
                        $ret = json_decode($res->getBody());
                        if($ret->nextAction->type==='otp') {
                            return [
                                'redirect_uri' => $redirect_uri
                            ];
                        }
                        return [
                            'status' => $ret->status,
                            'mode' => $ret->mode
                        ];
                    }
                    else {
                        return [
                            'redirect_uri' => $redirect_uri
                        ];
                    }
                }
            }
            $res = $client->request('post', '/api/v1/auth/contacts', [
                'json' => [
                    'email' => $sender->email,
                    'first_name' => $sender->first_name,
                    'last_name' => $sender->last_name,
                    'phone_number' => $clean_phone_number,
                    'country_code' => $sender_country->country_code,
                    'is_individual' => true
                ],
                'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $app_owner_token->access_token,
                    'X-APP-ID' => $this->app_id
                ]
            ]);
            $ret = json_decode($res->getBody());
            return [
                'redirect_uri' => $ret->redirect_url.'&redirect_uri='.urlencode(getenv('FUSIO_URL').'/operations/'.$this->app_key.'/'.$operation['id'].'/retry')
            ];
        }
    }
}
