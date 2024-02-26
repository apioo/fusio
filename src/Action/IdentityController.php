<?php
namespace App\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Exception\RequestException;
use Doctrine\DBAL\DriverManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use PSX\Framework\Util\Uuid;

class IdentityController extends ActionAbstract
{
    private $admin_token = false;

    protected $db;

    protected $app_id;

    protected $app_key;

    protected $app_owner_email;

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        if(!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return $this->response->build(422, [], [
                'message' => "Recipient's email is invalid"
            ]);
        }

        if(!$request->get('phone_number')) {
            return $this->response->build(422, [], [
                'message' => 'Phone number is required'
            ]);
        }

        if(strlen($request->get('phone_number'))>12) {
            return $this->response->build(422, [], [
                'message' => 'Phone number cannot contain more than 12 characters excluding the region prefix'
            ]);
        }

        $this->db = $this->connector->getConnection('System');

        $this->app_id = $context->getApp()->getId();
        $this->app_key = $context->getApp()->getAppKey();
        $this->app_owner_email = $context->getUser()->getEmail();

        $admin_token = $this->getAdminToken();

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

        $country = null;
        foreach($danapay_countries->data as $country) {
            if($country->country_code == $app_owner->country_code && $country->name == $app_owner->country) {
                //$country contains all data about cashin_methods and can check if the requested recipient can receive from the app owner
                $sender_country = $country;
                //check if recipient country is supported by sender country
                foreach($sender_country->receiving_countries as $receiving_country) {
                    if($receiving_country->receiving_country->code == $request->get('country')) {
                        $country = $receiving_country->receiving_country;
                        break;
                    }
                }
                break;
            }
        }
        if(!$country) {
            return $this->response->build(404, [], [
                'message' => 'Receiver country is invalid'
            ]);
        }

        $clean_phone_number = $request->get('phone_number');
	$request_body = $request->getBody();
	$company_name = isset($request_body['company_name']) ? $request_body['company_name'] : '';
	$first_name = isset($request_body['first_name']) ? $request_body['first_name'] : '';
	$last_name = isset($request_body['last_name']) ? $request_body['last_name'] : '';

        try {
            $res = $client->request('GET', '/api/v1/customers/exists?country_code='.$country->country_code.'&phone_number='.$clean_phone_number.'&first_name='.$first_name.'&last_name='.$last_name.'&company_name='.$company_name, [
                'headers' => 
                    [
                        'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                        'Authorization' => "Bearer " . $admin_token->access_token
                    ]
            ]);
            $customer = json_decode($res->getBody());
        }
        catch(ClientException $e) {
            return $this->response->build($e->getCode(), [], [
                'message' => 'Customer exists but some data mismatch with our records'
            ]);
        }

        if(!$customer->exists) {
            return $this->response->build(404, [], [
                'message' => 'Account unidentified'
            ]);
        }

        $row = $this->db->executeQuery("SELECT id FROM danapay_app_users WHERE app_id = :app_id AND user_id = :user_id", [
            'app_id' => $this->app_id,
            'user_id' => $customer->customer->id
        ])->fetchAssociative();
        if(!$row) {
            $this->db->executeQuery("INSERT INTO danapay_app_users (id, app_id, user_id, external_user_id, created_at) VALUES (:id, :app_id, :user_id, :external_user_id, NOW()) ON DUPLICATE KEY UPDATE external_user_id = :external_user_id", [
                'id' => Uuid::timeBased(),
                'app_id' => $this->app_id,
                'user_id' => $customer->customer->id,
                'external_user_id' => $customer->customer->external_user_id
            ]);
            $row = $this->db->executeQuery("SELECT id FROM danapay_app_users WHERE app_id = :app_id AND user_id = :user_id", [
                'app_id' => $this->app_id,
                'user_id' => $customer->customer->id
            ])->fetchAssociative();
        }

        return $this->response->build(200, [], [
            'id' => $row['id']
        ]);
    }

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
}
