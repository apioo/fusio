<?php

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
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
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
