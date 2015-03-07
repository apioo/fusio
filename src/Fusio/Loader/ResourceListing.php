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

namespace Fusio\Loader;

use PSX\Api\ResourceListing as PSXResourceListing;
use PSX\Api\ResourceListing\Resource;
use PSX\Api\View;
use PSX\Api\DocumentedInterface;
use PSX\Api\DocumentationInterface;
use PSX\Dispatch\ControllerFactoryInterface;
use PSX\Loader\Context;
use PSX\Loader\RoutingParserInterface;
use PSX\Loader\PathMatcher;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

/**
 * ResourceListing
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://phpsx.org
 */
class ResourceListing extends PSXResourceListing
{
	public function getResources(RequestInterface $request, ResponseInterface $response, Context $context)
	{
		$collections = $this->routingParser->getCollection();
		$result      = array();

		foreach($collections as $collection)
		{
			list($methods, $path, $source, $config) = $collection;

			$parts     = explode('::', $source, 2);
			$className = isset($parts[0]) ? $parts[0] : null;

			if(class_exists($className))
			{
				$ctx = clone $context;
				$ctx->set(Context::KEY_PATH, $path);
				$ctx->set('fusio.config', $config);

				$controller = $this->getController($className, $request, $response, $ctx);

				if($controller instanceof DocumentedInterface)
				{
					$name = substr(strrchr(get_class($controller), '\\'), 1);
					$doc  = $controller->getDocumentation();

					if($doc instanceof DocumentationInterface)
					{
						$result[] = new Resource(
							$name,
							$methods,
							$path,
							$source,
							$doc
						);
					}
				}
			}
		}

		return $result;
	}

	public function getResource($sourcePath, RequestInterface $request, ResponseInterface $response, Context $context)
	{
		$matcher     = new PathMatcher($sourcePath);
		$collections = $this->routingParser->getCollection();

		foreach($collections as $collection)
		{
			list($methods, $path, $source, $config) = $collection;

			$parts     = explode('::', $source, 2);
			$className = isset($parts[0]) ? $parts[0] : null;

			if(class_exists($className) && $matcher->match($path))
			{
				$ctx = clone $context;
				$ctx->set(Context::KEY_PATH, $path);
				$ctx->set('fusio.config', $config);

				$controller = $this->getController($className, $request, $response, $ctx);

				if($controller instanceof DocumentedInterface)
				{
					$name = substr(strrchr(get_class($controller), '\\'), 1);
					$doc  = $controller->getDocumentation();

					if($doc instanceof DocumentationInterface)
					{
						return new Resource(
							$name,
							$methods,
							$path,
							$source,
							$doc
						);
					}
				}
			}
		}

		return null;
	}
}
