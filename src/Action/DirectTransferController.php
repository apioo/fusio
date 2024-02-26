<?php
namespace App\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use GuzzleHttp\Exception\RequestException;

class DirectTransferController extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $order_reference = $request->get('order_reference');
        $receiver = $request->get('recipient');
        $amount = $request->get("amount");
        $operation = new Transfer($this->connector, $context->getApp()->getId(), $context->getApp()->getAppKey(), $context->getUser()->getEmail(), $this->response);
        try {
            return $operation->executeDirect($order_reference, $receiver, $amount, $request->getPayload()->getProperties());
        }
        catch(RequestException $e) {
            return $this->response->build($e->getCode(), [], getenv('DEBUG') ? $e : 'Remote server failed returning full response.');
        }
    }
} 
