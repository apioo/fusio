<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\ContextInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OperationController extends DanapayController
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        if($request->getContext()->getRequest()->getMethod()=='GET') {
            if($request->get('id')) {
                return $this->show($request, $configuration, $context);
            }
            else {
                return $this->index($request, $configuration, $context);
            }
        }
    }

    private function index(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $app_id = $context->getApp()->getId();
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
        $token = $this->getUserToken($app_owner->id, $app_id);
        $db = $this->connector->getConnection('System');
        //get the app destination of the call
        $perpage = 10;
        $page = 1;
        if($request->get('page')) {
            $page = intval($request->get('page'));
        }
        $offset = ($page-1)*$perpage;
        $total = $db->executeQuery("SELECT COUNT(*) AS n FROM danapay_app_operations WHERE app_id = :app_id", ['app_id' => $app_id])->fetchAssociative();
        $transactions = $db->executeQuery("SELECT payment_id FROM danapay_app_operations WHERE app_id = :app_id ORDER BY created_at DESC LIMIT ".$offset.",".$perpage, [
            'app_id' => $app_id
        ])->fetchAllAssociative();
        $qs = [];
        if($transactions) {
            foreach($transactions as $transaction) {
                $qs[] = $transaction['payment_id'];
            }
        }
        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);
        $res = $client->request('GET', '/api/v1/operations?page=' . $page, [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $token->access_token
                ],
            'json' => [
                'payment_ids' => $qs
            ]
        ]);
        $ret = json_decode($res->getBody());
        $ar = [];
        if(isset($ret->data)) {
            foreach($ret->data as $d) {
                $payment = $db->executeQuery("SELECT id FROM danapay_app_operations WHERE app_id = :app_id AND payment_id = :payment_id", [
                    'app_id' => $app_id,
                    'payment_id' => $d->payment_id
                ])->fetchAssociative();
                $ar[] = [
                    'id' => $payment['id'],
                    'type' => $d->transfer->type,
                    'started_at' => $d->created_at,
                    'country' => $d->type == 'Cashout' ? $d->source_user->country : $d->destination_user->country,
                    'initiator' => $d->initiator->full_name,
                    'amount' => $d->amount,
                    'status' => $d->status
                ];
            }
        }
        return [
            'has_more' => $page*$perpage<$total['n'] && count($ret->data)==$perpage,
            'data' => $ar
        ];
    }

    private function show(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $app_id = $context->getApp()->getId();
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
        $token = $this->getUserToken($app_owner->id, $app_id);
        $db = $this->connector->getConnection('System');
        $transaction = $db->executeQuery("SELECT payment_id FROM danapay_app_operations WHERE app_id = :app_id AND id = :id ", [
            'app_id' => $app_id,
            'id' => $request->get('id')
        ])->fetchAssociative();
        $qs = [];
        if(!$transaction) {
            return $this->response->build(404, [], [
                'message' => 'Operation does not exist'
            ]);
        }
        $client = new Client([
            'base_uri' => getenv('DANAPAY_API_ENDPOINT')
        ]);
        $res = $client->request('GET', '/api/v1/operations/' . $transaction['payment_id'], [
            'headers' => 
                [
                    'X-API-SECRET' => getenv('DANAPAY_API_SECRET'),
                    'Authorization' => "Bearer " . $token->access_token
                ],
            'json' => [
                'payment_ids' => $qs
            ]
        ]);
        $d = json_decode($res->getBody());
        $payment = $db->executeQuery("SELECT id FROM danapay_app_operations WHERE app_id = :app_id AND payment_id = :payment_id", [
            'app_id' => $app_id,
            'payment_id' => $d->payment_id
        ])->fetchAssociative();
        return [
            'id' => $payment['id'],
            'history' => $d->history,
            'payment_mode' => $d->type,
            'reason' => $d->transfer->reason,
            'amount' => $d->amount_in_euros,
            'sent_amount' => $d->amount_without_fee,
            'fee' => doubleval($d->amount_in_euros) - doubleval($d->amount_without_fee),
            'received_amount' => $d->amount_in_receiver_currency,
            'status' => $d->status,
            'recipients' => $d->transfer->type == 'Cashout' ? null : [[
                'name' => $d->destination_user->full_name,
                'email' => $d->destination_user->email
            ]]
        ];
    }
}