<?php
namespace App\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Exception\RequestException;

class TransferController extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $order_reference = $request->get('order_reference');
        $receiver = $request->get('recipient');
        $amount = $request->get("amount");

        $operation = new Transfer($this->connector, $context->getApp()->getId(), $context->getApp()->getAppKey(), $context->getUser()->getEmail(), $this->response);

        if($receiver) {
            if(!filter_var($receiver->email, FILTER_VALIDATE_EMAIL)) {
                return $this->response->build(422, [], [
                    'message' => "Recipient's email is invalid"
                ]);
            }
    
            if(!$receiver->phone_number) {
                return $this->response->build(422, [], [
                    'message' => 'Phone number is required'
                ]);
            }
    
            if(strlen($receiver->phone_number)>12) {
                return $this->response->build(422, [], [
                    'message' => 'Phone number cannot contain more than 12 characters excluding the region prefix'
                ]);
            }
            try {
                return $operation->execute($order_reference, $receiver, $amount, $request->getPayload()->getProperties());
            }
            catch(RequestException $e) {
                return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Remote server failed returning full response.');
            }
        }
        elseif($request->get('recipient_id')) {
            try {
                return $operation->executeById($order_reference, $request->get('recipient_id'), $amount, $request->getPayload()->getProperties());
            }
            catch(RequestException $e) {
                return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Remote server failed returning full response.');
            }
        }
    }
} 
