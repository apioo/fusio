<?php
/*
 * Fusio
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

use Fusio\Response;
use Fusio\Parameters;
use Fusio\Body;
use Fusio\Backend\Table\App\Token;
use PSX\Api\DocumentedInterface;
use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Controller\ApiAbstract;
use PSX\Data\Record;
use Fusio\Authorization\Oauth2Filter;
use PSX\Dispatch\Filter\UserAgentEnforcer;
use PSX\Http\Exception as StatusCode;
use PSX\Data\Schema\InvalidSchemaException;
use PSX\Loader\Context;
use PSX\Oauth2\Authorization\Exception\InvalidScopeException;

/**
 * SchemaApiController
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class SchemaApiController extends ApiAbstract implements DocumentedInterface
{
	const SCHEMA_PASSTHRU = 1;

	/**
	 * @Inject
	 * @var PSX\Data\Schema\Assimilator
	 */
	protected $schemaAssimilator;

	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 * @var Fusio\Processor
	 */
	protected $processor;

	/**
	 * @Inject
	 * @var Fusio\Data\SchemaManager
	 */
	protected $apiSchemaManager;

	/**
	 * @Inject
	 * @var Fusio\Logger
	 */
	protected $apiLogger;

	/**
	 * @Inject
	 * @var Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * @var PSX\Data\Record
	 */
	protected $routeConfig;

	/**
	 * @var integer
	 */
	protected $appId;

	public function onLoad()
	{
		parent::onLoad();

		// log request
		$this->apiLogger->log(
			$this->appId,
			$this->context->get('fusio.routeId'),
			$_SERVER['REMOTE_ADDR'],
			$this->request
		);

		// read request data
		if(!in_array($this->request->getMethod(), ['HEAD', 'GET']) && $this->getRouteConfiguration('request') > 0)
		{
			if($this->getRouteConfiguration('request', $this->request->getMethod()) == self::SCHEMA_PASSTHRU)
			{
				$request = $this->getBody();
			}
			else
			{
				$request = $this->import($this->apiSchemaManager->getSchema($this->getRouteConfiguration('request', $this->request->getMethod())));
			}
		}
		else
		{
			$request = new Record();
		}

		// execute action
		if($this->getRouteConfiguration('action') > 0)
		{
			$parameters = new Parameters(array_merge($this->getParameters(), $this->uriFragments));
			$body       = new Body($request);
			$response   = $this->processor->execute($this->getRouteConfiguration('action', $this->request->getMethod()), $parameters, $body);
		}
		else
		{
			throw new StatusCode\ServiceUnavailableException('No action provided');
		}

		// send response
		if($response instanceof Response && $this->getRouteConfiguration('response', $this->request->getMethod()) > 0)
		{
			$this->setResponseCode($response->getStatusCode() ?: 200);

			$headers = $response->getHeaders();
			foreach($headers as $name => $value)
			{
				$this->response->setHeader($name, $value);
			}

			if($this->getRouteConfiguration('response', $this->request->getMethod()) == self::SCHEMA_PASSTHRU)
			{
				$this->setBody($response->getBody());
			}
			else
			{
				$this->setBody($this->schemaAssimilator->assimilate($this->apiSchemaManager->getSchema($this->getRouteConfiguration('response', $this->request->getMethod())), $response->getBody()));
			}
		}
		else
		{
			$this->setResponseCode(204);
			$this->setBody('');
		}
	}

	public function getPreFilter()
	{
		$isPublic = (bool) $this->getRouteConfiguration('public', $this->request->getMethod());
		$filter   = array();

		// it is required for every request to have an user agent which 
		// identifies the client
		$filter[] = new UserAgentEnforcer();

		if(!$isPublic)
		{
			$filter[] = new Oauth2Filter($this->connection, $this->request->getMethod(), $this->context->get('fusio.routeId'), function($accessToken){

				$this->appId = $accessToken['appId'];

			});
		}

		return $filter;
	}

	public function getDocumentation()
	{
		$resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));
		$methods  = array('GET', 'POST', 'PUT', 'DELETE');

		foreach($methods as $methodName)
		{
			$method    = Resource\Factory::getMethod($methodName);
			$hasSchema = false;

			if($this->getRouteConfiguration('request', $methodName) > 0)
			{
				try
				{
					$method->setRequest($this->apiSchemaManager->getSchema($this->getRouteConfiguration('request', $methodName)));

					$hasSchema = true;
				}
				catch(InvalidSchemaException $e)
				{
				}
			}

			if($this->getRouteConfiguration('response', $methodName) > 0)
			{
				try
				{
					$method->addResponse(200, $this->apiSchemaManager->getSchema($this->getRouteConfiguration('response', $methodName)));

					$hasSchema = true;
				}
				catch(InvalidSchemaException $e)
				{
				}
			}

			if($hasSchema)
			{
				$resource->addMethod($method);
			}
		}

		return new Documentation\Simple($resource);
	}

	/**
	 * Returns an config value for the current router configuration. Currently
	 * this can be one of: public, request, response and action
	 *
	 * @param string $key
	 * @param string $requestMethod
	 * @return mixed
	 */
	protected function getRouteConfiguration($key, $requestMethod)
	{
		if($this->routeConfig === null)
		{
			$config = $this->context->get('fusio.config');

			if(!empty($config) && is_array($config))
			{
				foreach($config as $record)
				{
					if($record->getMethod() == $requestMethod)
					{
						$this->routeConfig = $record;
						break;
					}
				}
			}
		}

		if($this->routeConfig !== null)
		{
			return $this->routeConfig->getProperty($key);
		}

		return null;
	}

	protected function getVersion(DocumentationInterface $doc)
	{
		if($doc->isVersionRequired())
		{
			$accept  = $this->getHeader('Accept');
			$matches = array();

			preg_match('/^application\/vnd\.([a-z.-_]+)\.v([\d]+)\+([a-z]+)$/', $accept, $matches);

			$name    = isset($matches[1]) ? $matches[1] : null;
			$version = isset($matches[2]) ? $matches[2] : null;
			$format  = isset($matches[3]) ? $matches[3] : null;

			if($version !== null)
			{
				return new Version((int) $version);
			}
			else
			{
				// it is strongly recommended that clients specify an explicit
				// version but forcing that with an exception is not a good user
				// experience therefore we use the latest version if nothing is 
				// specified
				return new Version($doc->getLatestVersion());

				//throw new StatusCode\UnsupportedMediaTypeException('Requires an Accept header containing an explicit version');
			}
		}
		else
		{
			return new Version(1);
		}
	}
}
