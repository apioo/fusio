<?php

namespace Fusio;

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
		// check method
		$allowedMethods = explode(',', $api->getAllowedMethod());

		if (in_array($request->getMethod(), $allowedMethods)) {
			throw new InvalidRequestMethod('Invalid request method ' . $request->getMethod(), $request->getMethod(), $allowedMethods);
		}

		// get api
		$api = $this->getApiByRequest($request);

		// check right
		if ($this->appManager->hasPermission($app, $api, $request->getMethod())) {
			throw new PermissionException('Not allowed', 403);
		}

		// check request limit
		if ($this->apiManager->hasRequestLimitExceeded($app, $request->getMethod())) {
			throw new RequestLimitExceededException('Request limit exceeded', 403);
		}

		// execute action
		$this->executeAction($api, $request);

		// call trigger

		return $response;
	}

	protected function getApiByRequest(Request $request)
	{
		$api = $this->apiRepository->findOneByPath($request->getUrl()->getPath());

		if ($api instanceof Api) {
			return $api;
		}

		throw new InvalidRequestPath('Request path not found', 404);
	}

	protected function hasRequestLimitExceeded(App $app, $requestMethod)
	{
		
	}

	protected function executeAction(Api $api, Request $request)
	{
		$actions = $api->getActions();
		foreach ($actions as $action) {
			if ($action->getMethod() == $request->getMethod()) {
				$className = $action->getAction()->getClass();
				if (class_exists($className)) {
					$actionObject = new $className();
					if ($actionObject instanceof ActionInterface) {
						return $actionObject->execute($request, $action->getParam());
					}
				}
			}
		}

		throw new InvalidActionAttached('No action was attached to this request method', 500);
	}

	protected function executeTrigger(Api $api, $requestMethod)
	{
		$triggers = $api->getTrigger();

		foreach ($triggers as $trigger) {
			
		}
	}
}
