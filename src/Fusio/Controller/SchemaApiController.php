<?php

namespace Fusio\Controller;

use Fusio\Response;
use Fusio\Parameters;
use Fusio\Body;
use PSX\Api\DocumentedInterface;
use PSX\Api\Documentation;
use PSX\Api\View;
use PSX\ControllerAbstract;
use PSX\Data\Record;
use PSX\Http\Exception as StatusCode;
use PSX\Data\Schema\InvalidSchemaException;

class SchemaApiController extends ControllerAbstract implements DocumentedInterface
{
	const SCHEMA_PASSTHRU = 1;

	/**
	 * @Inject
	 * @var PSX\Data\Schema\Assimilator
	 */
	protected $schemaAssimilator;

	/**
	 * @Inject
	 * @var Fusio\Executor
	 */
	protected $executor;

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

	public function onLoad()
	{
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
			if($responseSchemaId == self::SCHEMA_PASSTHRU)
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
			$parameters = new Parameters($this->request->getQueryParams());
			$body       = new Body($request);
			$response   = $this->executor->execute($actionId, $parameters, $body);
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
				$this->setBody($this->schemaAssimilator->assimilate($response->getBody(), $this->apiSchemaManager->getSchema($responseSchemaId)));
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
		$view    = new View();
		$methods = array(
			View::METHOD_GET    => 'GET', 
			View::METHOD_POST   => 'POST', 
			View::METHOD_PUT    => 'PUT', 
			View::METHOD_DELETE => 'DELETE'
		);

		foreach($methods as $type => $method)
		{
			list($requestSchemaId, $responseSchemaId, $actionId) = $this->getConfiguration($method);

			if(!empty($requestSchemaId))
			{
				try
				{
					$view->set($type | View::TYPE_REQUEST, $this->apiSchemaManager->getSchema($requestSchemaId));
				}
				catch(InvalidSchemaException $e)
				{
				}
			}

			if(!empty($responseSchemaId))
			{
				try
				{
					$view->set($type | View::TYPE_RESPONSE, $this->apiSchemaManager->getSchema($responseSchemaId));
				}
				catch(InvalidSchemaException $e)
				{
				}
			}
		}

		return new Documentation\Simple($view);
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
