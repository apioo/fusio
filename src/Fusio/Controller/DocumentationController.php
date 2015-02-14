<?php

namespace Fusio\Controller;

use PSX\Api\DocumentedInterface;
use PSX\Controller\Tool\DocumentationController as DocController;

class DocumentationController extends DocController
{
	protected function getControllerInstance(array $route, $sourcePath = null)
	{
		list($methods, $path, $source, $config) = $route;

		$parts     = explode('::', $source, 2);
		$className = isset($parts[0]) ? $parts[0] : null;

		if(class_exists($className) && ($sourcePath === null || $sourcePath == ltrim($path, '/')))
		{
			$context = clone $this->context;
			$context->set('fusio.config', $config);

			$controller = $this->controllerFactory->getController($className, $this->request, $this->response, $context);

			if($controller instanceof DocumentedInterface)
			{
				return $controller;
			}
		}

		return null;
	}
}
