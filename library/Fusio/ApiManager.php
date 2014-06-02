<?php

namespace Fusio;

use Fusio\Api\InvalidRequestMethodException;
use PSX\Http\Request;
use PSX\Http\Response;

class ApiManager
{
	protected $appManager;

	public function __construct(AppManager $appManager)
	{
		$this->appManager = $appManager;
	}

	public function executeRequest(App $app, Request $request, Response $response)
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

		// execute
		$this->execute($api, $app, $request, $response);
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

	protected function execute(Api $api, App $app, Request $request, Response $response)
	{
		$context  = new Context($api, $app, $request, $response);
		$methods  = $api->getMethods();
		$model    = null;
		$match    = false;

		foreach($methods as $methodEntity)
		{
			if($methodEntity->getMethod() == $request->getMethod())
			{
				$parserEntity = $methodEntity->getParser();

				if($parserEntity instanceof Parser)
				{
					$parameters = $this->getParameters($parserEntity->getParam());
					$parser     = $this->parserFactory->factory($parserEntity->getType());
					$model      = $parser->transform($request, $parameters, $parser->getModel(), $context);
				}

				$viewEntity = $methodEntity->getView();

				if($viewEntity instanceof View)
				{
					$parameters = $this->getParameters($viewEntity->getParam());
					$view       = $this->viewFactory->factory($viewEntity->getType());
					$view->generate($request, $response, $parameters, $parser->getModel(), $context);
				}

				$match = true;
				break;
			}
		}

		if(!$match)
		{
			throw new InvalidRequestMethodException('Request method not allowed', 405, $request->getMethod(), $api->getAllowedMethods());
		}

		// call fitting triggers
		$triggers = $api->getTrigger();

		foreach($triggers as $triggerEntity)
		{
			try
			{
				if($triggerEntity->getMethod() == $request->getMethod())
				{
					$parameters = $this->getParameters($triggerEntity->getParam());
					$trigger    = $this->triggerFactory->factory($triggerEntity->getType());
					$trigger->execute($model, $parameters, $context);
				}
			}
			catch(\Exception $e)
			{
				// @TODO log error
			}
		}

		return $response;
	}

	protected function getParameters($param)
	{
		$parameters = array();

		if(!empty($param))
		{
			$parameters = json_decode($param, true);
		}

		return $parameters;
	}
}
