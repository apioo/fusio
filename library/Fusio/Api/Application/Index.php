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
			$app      = $this->getAppManager()->getApp($this->getContainer()->getParameter('app.id'));
			$response = $this->getApiManager()->executeRequest($app, $this->request);

			$this->setBody($response);
		}
		catch(\Exception $e)
		{
			$code    = isset(Http::$codes[$e->getCode()]) ? $e->getCode() : 500;
			$message = new Message($e->getMessage(), true);

			$this->setResponse($message, $code);
		}
	}

	/*
	public function onGet()
	{
		try
		{
			// check method
			if (!$this->api->isMethodAllowed(Api::GET)) {
				throw new InvalidMethodException('Method is not allowed', 405);
			}

			// check right
			if ($this->appManager->hasPermission($this->api->getId(), Api::GET)) {
				throw new PermissionException('Not allowed', 403);
			}

			// check request limit
			if ($this->apiManager->hasRequestLimitExceeded($this->app, Api::GET)) {
				throw new RequestLimitExceededException('Request limit exceeded', 403);
			}

			// get result
			$params    = $this->getRequestParams();
			$condition = $this->getRequestCondition();

			$result = $this->api->getDatasource()->getAll($params['fields'], $params['startIndex'], $params['count'], $params['sortBy'], $params['sortOrder'], $condition);

			$this->setResponse($result);
		}
		catch(\Exception $e)
		{
			$code    = isset(Http::$codes[$e->getCode()]) ? $e->getCode() : 500;
			$message = new Message($e->getMessage(), true);

			$this->setResponse($message, $code);
		}
	}

	public function onPost()
	{
		try
		{
			// check method
			if (!$this->api->isMethodAllowed(Api::POST)) {
				throw new InvalidMethodException('Method is not allowed', 405);
			}

			// check right
			if ($this->appManager->hasRight($this->api->getId(), Api::POST)) {
				throw new PermissionException('Not allowed', 403);
			}

			// check request limit
			if ($this->apiManager->hasRequestLimitExceeded($this->app, Api::POST)) {
				throw new RequestLimitExceededException('Request limit exceeded', 403);
			}

			// apply field mapping
			$record = $this->api->getMapping()->apply($record);

			// validate fields after rules
			$this->api->getValidator()->apply($record);

			// insert record
			$this->api->getDatasource()->insert($record);

			// send response
			$message = new Message('Insert successful', true);

			$this->setResponse($message, 200);
		}
		catch(\Exception $e)
		{
			$code    = isset(Http::$codes[$e->getCode()]) ? $e->getCode() : 500;
			$message = new Message($e->getMessage(), false);

			$this->setResponse($message, $code);
		}
	}

	public function onPut()
	{
		try
		{
			// check method
			if (!$this->api->isMethodAllowed(Api::PUT)) {
				throw new InvalidMethodException('Method is not allowed', 405);
			}

			// check right
			if ($this->appManager->hasRight($this->api->getId(), Api::PUT)) {
				throw new PermissionException('Not allowed', 403);
			}

			// check request limit
			if ($this->apiManager->hasRequestLimitExceeded($this->app, Api::PUT)) {
				throw new RequestLimitExceededException('Request limit exceeded', 403);
			}

			// apply field mapping
			$record = $this->api->getMapping()->apply($record);

			// validate fields after rules
			$this->api->getValidator()->apply($record);

			// update record
			$this->api->getDatasource()->update($record);

			// send response
			$message = new Message('Update successful', true);

			$this->setResponse($message, 200);
		}
		catch(\Exception $e)
		{
			$code    = isset(Http::$codes[$e->getCode()]) ? $e->getCode() : 500;
			$message = new Message($e->getMessage(), true);

			$this->setResponse($message, $code);
		}
	}

	public function onDelete()
	{
		try
		{
			// check method
			if (!$this->api->isMethodAllowed(Api::DELETE)) {
				throw new InvalidMethodException('Method is not allowed', 405);
			}

			// check right
			if ($this->app->hasRight($this->api->getId(), Api::DELETE)) {
				throw new PermissionException('Not allowed', 403);
			}

			// check request limit
			if ($this->api->hasRequestLimitExceeded($this->app, Api::DELETE)) {
				throw new RequestLimitExceededException('Request limit exceeded', 403);
			}

			// apply field mapping
			$record = $this->api->getMapping()->apply($record);

			// validate fields after rules
			$this->api->getValidator()->apply($record);

			// delete record
			$this->api->getDatasource()->delete($record);

			// send response
			$message = new Message('Delete successful', true);

			$this->setResponse($message, 200);
		}
		catch(\Exception $e)
		{
			$code    = isset(Http::$codes[$e->getCode()]) ? $e->getCode() : 500;
			$message = new Message($e->getMessage(), true);

			$this->setResponse($message, $code);
		}
	}
	*/
}
