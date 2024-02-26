<?php
namespace App\Action;

use GuzzleHttp\Client;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class CountrySpecController extends DanapayController
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        if($request->getContext()->getRequest()->getMethod()=='GET') {
            if($request->get('iso_code')) {
                return $this->show($request, $configuration, $context);
            }
            else {
                return $this->index($request, $configuration, $context);
            }
        }
    }

    private function getCountry($danapay_country_code) {
	if(!$danapay_country_code) {
		return [
			'code' => ''
		];
	}
        foreach(Library::COUNTRIES as $country) {
            if($country['dial_code'] == $danapay_country_code) {
                return $country;
            } 
        }
        throw new \Exception('Unknown country code ' . $danapay_country_code);
    }

    private function index(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
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
        $ar = [
            'has_more' => $danapay_countries->current_page < $danapay_countries->last_page,
            'data' => []
        ];
        $operators = [];
        try {
            $client = new Client([
                'base_uri' => 'https://api.hub2.io'
            ]);
            $res = $client->request('GET', '/data/providers');
            $providers = json_decode($res->getBody());
            foreach($providers as $provider) {
                if($provider->method == 'mobile_money') {
                    $operators[$provider->country][] = $provider->name;
                }
            }
        }
        catch(\Exception $e) {

        }
        foreach($danapay_countries->data as $_country) {
            if($_country->is_sending_country) {
                try {
                    $country = $this->getCountry($_country->country_code);
                    $d = [
                        'iso_code' => $country['code'],
                        'default_currency' => 'eur',
                        'supported_transfer_countries' => [],
                        'supported_payment_methods' => [],
                        'mobile_money_operators' => []
                    ];
                    foreach($_country->receiving_countries as $receiving_country) {
                        try {
                            $d['supported_transfer_countries'][] = $this->getCountry($receiving_country->receiving_country->country_code)['code'];
                        }
                        catch(\Exception $e) {
                            
                        }
                    }
                    foreach($_country->cash_in_methods as $cashing_method) {
                        $supported_payment_system = [
                            'min_amount' => $cashing_method->cash_in_method->min_amount,
                            'max_amount' => $cashing_method->cash_in_method->max_amount
                        ];
                        if($cashing_method->cash_in_method->payment_provider->name == 'Hub2') {
                            $supported_payment_system['available_operators'] = isset($operators[$country['code']]) ? $operators[$country['code']] : [];
                        }
                        $d['supported_payment_methods'][$cashing_method->cash_in_method->name] = $supported_payment_system;
                    }
                    $ar['data'][] = $d;
                }
                catch(\Exception $e) {

                }
            }
        }
        return $this->response->build(200, [], $ar);
    }

    private function show(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context) {
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
        foreach(Library::COUNTRIES as $country) {
            if(strtolower($country['code']) == strtolower($request->get('iso_code'))) {
                break;
            } 
        }
        $operators = [];
        try {
            $client = new Client([
                'base_uri' => 'https://api.hub2.io'
            ]);
            $res = $client->request('GET', '/data/providers');
            $providers = json_decode($res->getBody());
            foreach($providers as $provider) {
                if($provider->method == 'mobile_money') {
                    $operators[$provider->country][] = $provider->name;
                }
            }
        }
        catch(\Exception $e) {

        }
        foreach($danapay_countries->data as $_country) {
            if($_country->country_code==$country['dial_code']) {
                $d = [
                    'iso_code' => $country['code'],
                    'default_currency' => 'eur',
                    'supported_transfer_countries' => [],
                    'supported_payment_methods' => []
                ];
                foreach($_country->receiving_countries as $receiving_country) {
                    $d['supported_transfer_countries'][] = $this->getCountry($receiving_country->receiving_country->country_code)['code'];
                }
                foreach($_country->cash_in_methods as $cashing_method) {
                    $supported_payment_system = [
                        'min_amount' => $cashing_method->cash_in_method->min_amount,
                        'max_amount' => $cashing_method->cash_in_method->max_amount
                    ];
                    if($cashing_method->cash_in_method->payment_provider->name == 'Hub2') {
                        $supported_payment_system['available_operators'] = isset($operators[$country['code']]) ? $operators[$country['code']] : [];
                    }
                    $d['supported_payment_methods'][$cashing_method->cash_in_method->name] = $supported_payment_system;
                }
                return $this->response->build(200, [], $d);
            }
        }
        return $this->response->build(404, [], [
            'message' => 'Country is not yet supported'
        ]);
    }
}
