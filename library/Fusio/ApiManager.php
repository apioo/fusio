<?php

namespace Fusio;

use Fusio\Api\InvalidRequestMethodException;
use PSX\Http\Request;

class ApiManager
{
	protected $appManager;

	public function __construct(AppManager $appManager)
	{
		$this->appManager = $appManager;
	}

	public function executeRequest(App $app, Request $request)
	{
		// get api
		$api = $this->getApiByPath($this->getUriFragments('path'));

		// check method
		$allowedMethods = $api->getAllowedMethods();
		if (in_array($request->getMethod(), $allowedMethods)) {
			throw new InvalidRequestMethodException('Request method ' . $request->getMethod() . ' not allowed', 405, $request->getMethod(), $allowedMethods);
		}

		// check right
		if ($this->appManager->hasPermission($app, $api, $request->getMethod())) {
			throw new ForbiddenException('Not allowed', 403);
		}

		// check request limit
		if ($this->apiManager->hasRequestLimitExceeded($app, $request->getMethod())) {
			throw new RequestLimitExceededException('Request limit exceeded', 403);
		}

		// execute action
		$this->executeAction($api, $app, $request);

		// call trigger

		return $response;
	}

	protected function getApiByPath($path)
	{
		$api = $this->apiRepository->findOneByPath($path);

		if ($api instanceof Api) {
			return $api;
		}

		throw new InvalidRequestPath('Request path not found', 404);
	}

	protected function hasRequestLimitExceeded(App $app, $requestMethod)
	{
		
	}

	protected function executeAction(Api $api, App $app, Request $request)
	{
		// parse the model if we have an fitting request
		if(in_array($request->getMethod(), array('POST', 'PUT', 'DELETE')))
		{
			// @TOOD parse model into an record using $api->getModel();
		}

		// call fitting triggers
		$context  = new Context($api, $app);
		$triggers = $api->getTrigger();

		foreach($triggers as $triggerEntity)
		{
			try
			{
				if($triggerEntity->getMethod() == $request->getMethod())
				{
					$parameters = json_decode($triggerEntity->getParam(), true);

					$trigger = $this->triggerFactory->factory($triggerEntity->getType());
					$trigger->execute($request, $parameters, $context);
				}
			}
			catch(\Exception $e)
			{
				// @TODO log error
			}
		}
	}
}
