<?php
/*
 * fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Controller;

use PSX\Api\DocumentedInterface;
use PSX\Controller\Tool\DocumentationController as DocController;

/**
 * DocumentationController
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://phpsx.org
 */
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
