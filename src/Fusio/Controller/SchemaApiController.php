<?php

namespace Fusio\Controller;

use Fusio\Response;
use Fusio\Parameters;
use Fusio\Body;
use PSX\ControllerAbstract;
use PSX\Data\Record;
use PSX\Http\Exception as StatusCode;

class SchemaApiController extends ControllerAbstract
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

	public function onLoad()
	{
		$config = $this->context->get('fusio.config');
		$method = $this->request->getMethod();

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

		// read request data
		if(!in_array($method, ['HEAD', 'GET']) && !empty($requestSchemaId))
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
}
