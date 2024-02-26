<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Utils;
use PSX\Framework\Util\Uuid;

class CompanyController extends DanapayController
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        switch($request->getContext()->getRequest()->getMethod()) {
            case 'POST':
                if($request->get('id')) {
                    return $this->upload($request, $configuration, $context);
                }
                else {
                    return $this->store($request, $configuration, $context);
                }
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
        $rows = $db->executeQuery("SELECT id FROM danapay_app_companies WHERE app_id = :app_id LIMIT " . ((intval($page) - 1) * $perpage) . "," . $perpage, [
            'app_id' => $app_id
        ])->fetchAllAssociative();
        $count = $db->executeQuery("SELECT COUNT(*) AS n FROM danapay_app_companies WHERE app_id = :app_id", [
            'app_id' => $app_id
        ])->fetchAssociative();
        return [
            'has_more' => $count['n']>intval($page)*$perpage,
            'data' => $rows
        ];
    }

    public function store(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
        foreach(Library::COUNTRIES as $country) {
            if($country['code'] == $request->get('address')->city->country)
                break;
        }

        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');
        $row = $db->executeQuery("SELECT * FROM danapay_app_users WHERE `app_id` = :app_id AND `id` = :id", [
            'app_id' => $app_id,
            'id' => $request->get('owner_id')
        ])->fetchAssociative();
        $admin_token = $this->getAdminToken();
        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);
        try {
            $res = $client->request('POST', '/api/v1/companies/registerFromBackOffice', [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => 'Bearer ' . $admin_token->access_token
                    ]
                ,
                'json' => [
                    "registered_id" => $request->get('registration_id'),
                    "name" => $request->get('name'),
                    "target_user_id" => $row['user_id'],
                    "country_code" => $country['dial_code'],
                    "address" => $request->get('address')->street_name,
                    "street_number" => $request->get('address')->street_number,
                    "city" => $request->get('address')->city->name,
                    'postal_code' => $request->get('address')->postal_code
                ]
            ]);
            $ret = json_decode($res->getBody());
            if($ret->company) {
                $id = Uuid::timeBased();
                $db->executeStatement("INSERT INTO danapay_app_companies (`id`, `app_id`, `company_id`, `created_at`) VALUES (:id, :app_id, :company_id, NOW())", [
                    'id' => $id,
                    'app_id' => $app_id,
                    'company_id' => $ret->company->id
                ]);
                $ar = [
                    'id' => $id
                ];
                return $this->response->build(200, [], $ar);
            }
        }
        catch(RequestException $e) {
            return $this->response->build($e->getResponse()->getStatusCode(), [], [
                'message' => json_decode($e->getResponse()->getBody())->message
            ]);
        }
    }

    private function show(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');
        $row = $db->executeQuery("SELECT * FROM danapay_app_companies WHERE `app_id` = :app_id AND `id` = :id", [
            'app_id' => $app_id,
            'id' => $request->get('id')
        ])->fetchAssociative();
        if($row) {
            $admin_token = $this->getAdminToken();
            $client = new Client([
                'base_uri' => getenv('DANAPAY_API_ENDPOINT')
            ]);
            $res = $client->request('GET', '/api/v1/companies/' . $row['company_id'], [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => 'Bearer ' . $admin_token->access_token
                    ]
            ]);
            $ret = json_decode($res->getBody());
            $owner = $db->executeQuery("SELECT * FROM danapay_app_users WHERE `app_id` = :app_id AND `user_id` = :user_id", [
                'app_id' => $app_id,
                'user_id' => $ret->user_id
            ])->fetchAssociative();
            foreach(Library::COUNTRIES as $country) {
                if($country['name'] == $ret->country)
                    break;
            }
            $documents = [];
            foreach($ret->documents as $document) {
                $documents[] = [
                    'name' => $document->name,
                    'type' => $document->type
                ];
            }
            return $this->response->build(200, [], [
                'name' => $ret->name,
                'registration_id' => $ret->registered_id,
                'address' => [
                    'street_number' => $ret->street_number,
                    'street_name' => $ret->address,
                    'complement' => $ret->complement_address,
                    'postal_code' => $ret->postal_code,
                    'neighbourhood' => $ret->quarter,
                    'city' => [
                        'name' => $ret->city,
                        'country' => $country['code']
                    ]
                ],
                'active' => $ret->is_active,
                'verified' => $ret->is_verified,
                'archived' => boolval($ret->archived_at),
                'owner_id' => $owner['id'],
                'documents' => $documents,
              ]);
        }
        return $this->response->build(404, [], "Company not found");
    }

    public function update(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');
        $row = $db->executeQuery("SELECT * FROM danapay_app_companies WHERE `app_id` = :app_id AND `id` = :id", [
            'app_id' => $app_id,
            'id' => $request->get('id')
        ])->fetchAssociative();
        if($row) {
            foreach(Library::COUNTRIES as $country) {
                if($country['code'] == $request->get('address')->country)
                    break;
            }
            $token = $this->getAdminToken();
            $client = new Client([
                'base_uri' => getenv('DANAPAY_API_ENDPOINT')
            ]);
            $res = $client->request('PUT', '/api/v1/companies/' . $row['company_id'], [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => 'Bearer ' . $token->access_token
                    ]
                ,
                'json' => [
                    'name'=> $request->get('name'),
                    'address' => $request->get('address')->street_name,
                    'registered_id'=> $request->get('registration_id'),
                    'country'=> $country['name'],
                    'country_code'=> $country['dial_code'],
                    'address'=> $request->get('address')->street_name,
                    'city'=> $request->get('address')->city,
                    'quarter'=> $request->get('address')->neighbourhood,
                    'complement_address' => $request->get('address')->complement,
                    'postal_code' => $request->get('address')->postal_code,
                    'street_number' => $request->get('address')->street_number
                ]
            ]);
            $ret = json_decode($res->getBody());
            return $this->response->build(200, [], [
                'message' => $ret->message
            ]);
        }

        return $this->response->build(404, [], [
            'message' => 'Company not found'
        ]);
    }

    public function upload(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
        $app_id = $context->getApp()->getId();
        $db = $this->connector->getConnection('System');
        $row = $db->executeQuery("SELECT * FROM danapay_app_companies WHERE `app_id` = :app_id AND `id` = :id", [
            'app_id' => $app_id,
            'id' => $request->get('id')
        ])->fetchAssociative();
        if($row) {
            $token = $this->getAdminToken();
            $client = new Client([
                'base_uri' => getenv('DANAPAY_API_ENDPOINT')
            ]);
            try {
                $res = $client->request('POST', '/api/v1/companies/'.$row['company_id'].'/documents', [
                    'headers' => 
                        [
                            'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                            'Authorization' => 'Bearer ' . $token->access_token
                        ]
                    ,
                    'multipart' => [
                        [
                            'name' => 'type',
                            'contents' => $request->get('document')
                        ],
                        [
                            'name' => 'file',
                            'contents' => Utils::tryFopen($request->get('file')->tmp_name, 'r'),
                            'filename' => $request->get('file')->name
                        ]
                    ]
                ]);
                $ret = json_decode($res->getBody());
                if($ret->document) {
                    return $this->response->build(200, [], [
                        'message' => $ret->message
                    ]);
                }
            }
            catch(RequestException $e) {
                return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Could not upload company documents.'); 
            }
        }

        return $this->response->build(404, [], [
            'message' => 'Company not found'
        ]);
    }
} 