<?php
namespace App\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Exception\RequestException;

class MobilePaymentController extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $order_reference = $request->get('order_reference');
        $sender = $request->get('user');
        $amount = $request->get("amount");

        if(!filter_var($sender->email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->build(422, [], [
                'message' => "Sender's email is invalid"
            ]);
        }

        if(strlen($request->get('redirect_base'))>0 && !preg_match("/^https?:\/\//i", $request->get('redirect_base'))) {
            return $this->response->build(422, [], [
                'message' => 'redirect_base must be a full qualified url starting with http:// or https://'
            ]);
        }

        if(!$sender->phone_number) {
            return $this->response->build(422, [], [
                'message' => 'Phone number is required'
            ]);
        }

        if(strlen($sender->phone_number)>12) {
            return $this->response->build(422, [], [
                'message' => 'Phone number cannot contain more than 12 characters excluding the region prefix'
            ]);
        }

        $operation = new Payment($this->connector, $context->getApp()->getId(), $context->getApp()->getAppKey(), $context->getUser()->getEmail(), $this->response);        
        $payload = $request->getPayload()->getProperties();
        $payload['_payment_provider'] = 'Hub2';
        try {
            return $operation->execute($order_reference, $sender, $amount, $payload);
        }
        catch(RequestException $e) {
            return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Remote server failed returning full response.');
        }
    }
} 
