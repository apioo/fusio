<?php

namespace Fusio\Api\Application;

use PSX\Data\Message;
use PSX\Module\Api;
use PSX\Module\ApiAbstract;
use Fusio\Dispatch\RequestFilter\OauthAuthentication;

class Index extends ApiAbstract
{
	protected $am;
	protected $api;

	public function getRequestFilter()
	{
		$con    = $this->getContainer();
		$config = $this->config;
		$oauth  = new OauthAuthentication($this->getSql());

		$oauth->onSuccess(function($appId) use ($con, $config){
			$con->setParameter('app.id', $userId);
		});

		return array($oauth);
	}

	public function onLoad()
	{
		try
		{
			$this->getAppManager()->getApp($this->getContainer()->getParameter('app.id'));
			$this->getApiManager()->executeRequest($app, $this->request, $this->response);
		}
		catch(\Exception $e)
		{
			$code    = isset(Http::$codes[$e->getCode()]) ? $e->getCode() : 500;
			$message = new Message($e->getMessage(), true);

			$this->setResponse($message, $code);
		}
	}
}
