<?php

namespace Fusio\Controller;

use Fusio\Response;
use Fusio\Parameters;
use Fusio\Body;
use PSX\ControllerAbstract;
use PSX\Data\Record;

class SchemaApiController extends ControllerAbstract
{
	/**
	 * @Inject
	 * @var PSX\Data\Schema\Assimilator
	 */
	protected $schemaAssimilator;

	/**
	 * @Inject
	 * @var Fusio\ActionExecutor
	 */
	protected $actionExecutor;

	/**
	 * @Inject
	 * @var Fusio\Data\SchemaManager
	 */
	protected $apiSchemaManager;

	public function onLoad()
	{
		$config = $this->context->get('fusio.config');
		$method = $this->request->getMethod();

		foreach($config as $record)
		{
			if($record->getMethod() == $method)
			{
				$requestSchemaId  = $record->getRequest();
				$responseSchemaId = $record->getResponse();
				$actionId         = $record->getAction();
				break;
			}
		}

		// read request data
		if(!in_array($method, ['HEAD', 'GET']) && !empty($requestSchemaId))
		{
			$request = $this->import($this->apiSchemaManager->getSchema($requestSchemaId));
		}
		else
		{
			$request = new Record();
		}

		// execute action
		$parameters = new Parameters($this->request->getQueryParams());
		$body       = new Body($request);
		$response   = $this->actionExecutor->execute($actionId, $parameters, $body);

		// send response
		if($response instanceof Response && !empty($responseSchemaId))
		{
			$this->setResponseCode($response->getStatusCode() ?: 200);
			$this->setBody($this->schemaAssimilator->assimilate($response->getBody(), $this->apiSchemaManager->getSchema($responseSchemaId)));
		}
		else
		{
			//$this->setResponseCode(204);
			$this->setBody('');
		}
	}
}
