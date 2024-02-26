<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use PSX\Framework\Util\Uuid;

class CustomerController extends DanapayController
{
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

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        switch($request->getContext()->getRequest()->getMethod()) {
            case 'POST':
                return $this->store($request, $configuration, $context);
            case 'GET':
                if($request->get('id')) {
                    return $this->show($request, $configuration, $context);
                }
                else {
                    return $this->list($request, $configuration, $context);
                }
            case 'PUT':
                return $this->update($request, $configuration, $context);
        }
    }

    private function list(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
        $db = $this->connector->getConnection('System');
        $page = 1;
        if($request->get('page')) {
            $page = $request->get('page');
        }
        $perpage = 10;
        $app_id = $context->getApp()->getId();
        $rows = $db->executeQuery("SELECT id FROM danapay_app_users WHERE app_id = :app_id LIMIT " . ((intval($page) - 1) * $perpage) . "," . $perpage, [
            'app_id' => $app_id
        ])->fetchAllAssociative();
        $count = $db->executeQuery("SELECT COUNT(*) AS n FROM danapay_app_users WHERE app_id = :app_id", [
            'app_id' => $app_id
        ])->fetchAssociative();
        return [
            'has_more' => $count['n']>intval($page)*$perpage,
            'data' => $rows
        ];
    }

    private function store(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
        foreach(Library::COUNTRIES as $country) {
            if($country['code'] == $request->get('country'))
                break;
        }
        $db = $this->connector->getConnection('System');
        $clean_phone_number = $request->get('phone_number');
        if(!preg_match("/^\d+$/", $clean_phone_number) || strlen($clean_phone_number)>12) {
            return $this->response->build(422, [], [
                'message' => 'Phone number cannot contain more than 12 characters excluding the region prefix'
            ]);
        }
        $admin_token = $this->getAdminToken();
        $app_id = $context->getApp()->getId();
        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);
        //check if the user exists with such email, phone_number, country - using admin_token
        //get phone indicator for the specified country
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
        $app_owner_token = $this->getUserToken($app_owner->id, $app_id);
        try {
            $res = $client->request('GET', '/api/v1/users/byEmail/' . $request->get('email'), [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => "Bearer " . $admin_token->access_token
                    ]
            ]);
            $customer = json_decode($res->getBody());
            if($customer) {
                $row = $db->executeQuery("SELECT id FROM danapay_app_users WHERE app_id = :app_id AND user_id = :user_id", [
                    'app_id' => $app_id,
                    'user_id' => $customer->id
                ])->fetchAssociative();
                if(!$row) {
                    $db->executeQuery("INSERT INTO danapay_app_users (id, app_id, user_id, external_user_id, created_at) VALUES (:id, :app_id, :user_id, :external_user_id, NOW()) ON DUPLICATE KEY UPDATE external_user_id = :external_user_id", [
                        'id' => Uuid::timeBased(),
                        'app_id' => $app_id,
                        'user_id' => $customer->id,
                        'external_user_id' => $customer->external_user_id
                    ]);
                    $row = $db->executeQuery("SELECT id FROM danapay_app_users WHERE app_id = :app_id AND user_id = :user_id", [
                        'app_id' => $app_id,
                        'user_id' => $customer->id
                    ])->fetchAssociative();
                }
                else {
                    return $this->response->build(422, [], 'User with such email address already exists.'); 
                }
                return [
                    'id' => $row['id'],
                    'has_temporary_password' => boolval($customer->has_temporary_password),
                    'is_individual' => boolval($customer->is_individual),
                    'active' => $customer->is_active,
                    'verified' => boolval($customer->is_verified),
                    'archived' => $customer->is_archived
                ];
            }
        }
        catch(RequestException $e) {
            if($e->getCode()==404) {
                $res = $client->request('post', '/api/v1/auth/contacts', [
                    'json' => [
                        'email' => $request->get('email'),
                        'first_name' => $request->get('first_name'),
                        'last_name' => $request->get('last_name'),
                        'phone_number' => $clean_phone_number,
                        'country_code' => $country['dial_code']
                    ],
                    'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => "Bearer " . $app_owner_token->access_token,
                        'X-APP-ID' => $app_id
                    ]
                ]);
                $ret = json_decode($res->getBody());
                $id = Uuid::timeBased();
                $db->executeQuery("INSERT INTO danapay_app_users (id, app_id, user_id, external_user_id, created_at) VALUES (:id, :app_id, :user_id, :external_user_id, NOW())", [
                    'id' => $id,
                    'app_id' => $app_id,
                    'user_id' => $ret->beneficiary->id,
                    'external_user_id' => $ret->beneficiary->external_user_id
                ]);
                $customer = $ret->beneficiary;
                return [
                    'id' => $id,
                    'has_temporary_password' => boolval($customer->has_temporary_password),
                    'is_individual' => boolval($customer->is_individual),
                    'active' => $customer->is_active,
                    'verified' => boolval($customer->is_verified),
                    'archived' => $customer->is_archived,
                    'liveness_url' => $ret->redirect_url.'&redirect_uri='.urlencode(getenv('FUSIO_URL').'/json?redirect_uri='.urlencode($request->get('redirect_url')))
                ];
            }
            return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Something went wrong.'); 
        }
    }

    private function show(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');
        $row = $db->executeQuery("SELECT * FROM danapay_app_users WHERE `app_id` = :app_id AND `id` = :id", [
            'app_id' => $app_id,
            'id' => $request->get('id')
        ])->fetchAssociative();
        if($row) {
            $token = $this->getAdminToken();
            $client = new Client([
                'base_uri' => getenv('DANAPAY_API_ENDPOINT')
            ]);
            try {
                $res = $client->request('GET', '/api/v1/users/details/' . $row['external_user_id'], [
                    'headers' => 
                        [
                            'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                            'Authorization' => 'Bearer ' . $token->access_token
                        ]
                ]);
                $ret = json_decode($res->getBody());
                foreach(Library::COUNTRIES as $country) {
                    if($country['dial_code'] == $ret->country_code)
                        break;
                }
                return $this->response->build(200, [], [
                    'email' => $ret->email,
                    'first_name' => $ret->first_name,
                    'last_name' => $ret->last_name,
                    'gender' => $ret->gender,
                    'phone_number' => $ret->phone_number,
                    'country' => $country['code'],
                    'has_temporary_password' => boolval($ret->has_temporary_password),
                    'is_individual' => boolval($ret->is_individual),
                    'active' => boolval($ret->is_active),
                    'verified' => boolval($ret->is_verified),
                    'archived' => $ret->is_archived
                ]);
            }
            catch(ClientException $e) {
                return $this->response->build(404, [], [
                    'message' => 'User is disabled or deleted'
                ]);
            }
        }
        return $this->response->build(404, [], "User not found");
    }

    private function update(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
        foreach(Library::COUNTRIES as $country) {
            if($country['code'] == $request->get('country'))
                break;
        }
        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');
        $row = $db->executeQuery("SELECT * FROM danapay_app_users WHERE `app_id` = :app_id AND `id` = :id", [
            'app_id' => $app_id,
            'id' => $request->get('id')
        ])->fetchAssociative();

        if($row) {
            $token = $this->getAdminToken();
            $client = new Client([
                'base_uri' => getenv('DANAPAY_API_ENDPOINT')
            ]);
            $res = $client->request('PUT', '/api/v1/users/' . $row['user_id'], [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => 'Bearer ' . $token->access_token
                    ]
                ,
                'json' => [
                    'country_code' => $country['dial_code'],
                    "email"=> $request->get('email'),
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'phone_number' => $request->get('phone_number'),
                    'gender' => $request->get('gender'),
                    'reason_for_modification' => 'Queried by app #' . $context->getApp()->getName()
                ]
            ]);
            $ret = json_decode($res->getBody());
            if($ret->user) {
                return $this->response->build(200, [], [
                    'message' => $ret->message
                ]);
            }
        }

        return $this->response->build(404, [], [
            'message' => 'Customer not found'
        ]);
    }
} 