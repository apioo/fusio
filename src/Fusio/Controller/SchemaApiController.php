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
use PSX\Api\DocumentedInterface;
use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Controller\ApiAbstract;
use PSX\Data\Record;
use PSX\Http\Exception as StatusCode;
use PSX\Data\Schema\InvalidSchemaException;
use PSX\Loader\Context;

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

	public function onLoad()
	{
		parent::onLoad();

		list($requestSchemaId, $responseSchemaId, $actionId) = $this->getConfiguration($this->request->getMethod());

		// we get the appId from authentication
		$appId = null;

		// log request
		$this->apiLogger->log(
			$appId,
			$this->context->get('fusio.routeId'),
			$_SERVER['REMOTE_ADDR'],
			$this->request
		);

		// read request data
		if(!in_array($this->request->getMethod(), ['HEAD', 'GET']) && !empty($requestSchemaId))
		{
			if($requestSchemaId == self::SCHEMA_PASSTHRU)
			{
				$request = $this->getBody();
			}
			else
			{
				$request = $this->import($this->apiSchemaManager->getSchema($requestSchemaId));
			}
		}
		else
		{
			$request = new Record();
		}

		// execute action
		if(!empty($actionId))
		{
			$parameters = new Parameters(array_merge($this->getParameters(), $this->uriFragments));
			$body       = new Body($request);
			$response   = $this->processor->execute($actionId, $parameters, $body);
		}
		else
		{
			throw new StatusCode\ServiceUnavailableException('No action provided');
		}

		// send response
		if($response instanceof Response && !empty($responseSchemaId))
		{
			$this->setResponseCode($response->getStatusCode() ?: 200);

			$headers = $response->getHeaders();
			foreach($headers as $name => $value)
			{
				$this->response->setHeader($name, $value);
			}

			if($responseSchemaId == self::SCHEMA_PASSTHRU)
			{
				$this->setBody($response->getBody());
			}
			else
			{
				$this->setBody($this->schemaAssimilator->assimilate($this->apiSchemaManager->getSchema($responseSchemaId), $response->getBody()));
			}
		}
		else
		{
			$this->setResponseCode(204);
			$this->setBody('');
		}
	}

	public function getDocumentation()
	{
		$resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));
		$methods  = array('GET', 'POST', 'PUT', 'DELETE');

		foreach($methods as $methodName)
		{
			$method    = Resource\Factory::getMethod($methodName);
			$hasSchema = false;

			list($requestSchemaId, $responseSchemaId, $actionId) = $this->getConfiguration($methodName);

			if(!empty($requestSchemaId))
			{
				try
				{
					$method->setRequest($this->apiSchemaManager->getSchema($requestSchemaId));

					$hasSchema = true;
				}
				catch(InvalidSchemaException $e)
				{
				}
			}

			if(!empty($responseSchemaId))
			{
				try
				{
					$method->addResponse(200, $this->apiSchemaManager->getSchema($responseSchemaId));

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

	protected function getConfiguration($method)
	{
		$config           = $this->context->get('fusio.config');
		$requestSchemaId  = null;
		$responseSchemaId = null;
		$actionId         = null;

		if(!empty($config) && is_array($config))
		{
			foreach($config as $record)
			{
				if($record->getMethod() == $method)
				{
					$requestSchemaId  = (int) $record->getRequest();
					$responseSchemaId = (int) $record->getResponse();
					$actionId         = (int) $record->getAction();
					break;
				}
			}
		}

		return [$requestSchemaId, $responseSchemaId, $actionId];
	}
}
