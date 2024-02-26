<?php
namespace App\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Exception\RequestException;

class PaymentController extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $order_reference = $request->getPayload()->get('order_reference');
        $sender = $request->getPayload()->get('sender');
        $amount = $request->getPayload()->get("amount");
        
        if(strlen($request->getPayload()->get('redirect_base'))>0 && !preg_match("/^https?:\/\//i", $request->getPayload()->get('redirect_base'))) {
            return $this->response->build(422, [], [
                'message' => 'redirect_base must be a full qualified url starting with http:// or https://'
            ]);
        }

        $operation = new Payment($this->connector, $context->getApp()->getId(), $context->getApp()->getAppKey(), $context->getUser()->getEmail(), $this->response);
        $payload = $request->getPayload()->getProperties();

        if($sender) {
            if(!filter_var($sender->email, FILTER_VALIDATE_EMAIL)) {
                return $this->response->build(422, [], [
                    'message' => "Sender's email is invalid"
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
            $payload['user'] = $payload['sender'];
            unset($payload['sender']);
            try {
                return $operation->execute($order_reference, $sender, $amount, $payload);
            }
            catch(RequestException $e) {
                return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Remote server failed returning full response.');
            }
        }
        else {
            return $operation->anonymousLink($order_reference, $amount, $payload);
        }
    }
} 
