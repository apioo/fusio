<?php
namespace App\Action;

use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Client;

class FeeController extends DanapayController
{
    private function getCountry($iso_code) {
        foreach(Library::COUNTRIES as $country) {
            if(strtolower($country['code']) == strtolower($iso_code)) {
                return $country;
            } 
        }
        throw new \Exception('Unknown country code');
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $admin_token = $this->getAdminToken();
        $from = $request->get('from');
        $to = $request->get('to');
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
        $s_country = $this->getCountry($from);
        $sender_countries = array_filter($danapay_countries->data, function($item)use($s_country){
            return $item->country_code == $s_country['dial_code'];
        });
        if(count($sender_countries)>0) {
            $sender_country = array_values($sender_countries)[0];
        }
        if(!$sender_country) {
            return $this->response->build(404, [], [
                'message' => 'Sender country is invalid'
            ]);
        }
        $country = $this->getCountry($to);
        $recipient_countries = array_filter($sender_country->receiving_countries, function($item)use($country){
            return $item->receiving_country->country_code == $country['dial_code'];
        });
        if(count($recipient_countries)>0) {
            $recipient_country = array_values($recipient_countries)[0];
        }
        if(!$recipient_country) {
            return $this->response->build(404, [], [
                'message' => 'Recipient country is invalid'
            ]);
        }
        $fees = [];
        foreach($sender_country->fees as $fee) {
            $fees[] = [
                'from' => $fee->min,
                'to' => $fee->max,
                'value' => $fee->value
            ];
        }
        return $this->response->build(200, [], [
            'fees' => $fees,
            'from' => [
                'name' => $sender_country->name,
                'min' => $sender_country->sending_min_amount,
                'max' => $sender_country->sending_max_amount
            ],
            'to' => [
                'name' => $recipient_country->receiving_country->name,
                'min' => $recipient_country->receiving_country->receiving_min_amount,
                'max' => $recipient_country->receiving_country->receiving_max_amount
            ]
        ]);
    }
}