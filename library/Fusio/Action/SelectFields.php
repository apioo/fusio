<?php

namespace Fusio\Action;

use Fusio\Connection\Factory;
use Fusio\Parameter;
use InvalidArgumentException;
use PSX\Http\Request;

class SelectFields extends ConnectionActionAbstract
{
	public function execute(Request $request, array $parameters)
	{
		$connectionId = isset($parameters['connection_id']) ? $parameters['connection_id'] : null;
		$table        = isset($parameters['table']) ? $parameters['table'] : null;
		$fields       = isset($parameters['fields']) ? $parameters['fields'] : null;

		// select fields
		return $this->getFields($this->getConnector($connectionId), $table, $fields, $request);
	}

	public function getParameters()
	{
		return array(
			new Parameter\Text('connection_id', 'Connection'),
			new Parameter\Text('table', 'Table'),
			new Parameter\Text('fields', 'Fields'),
		);
	}

	protected function getFields(ConnectorInterface $connector, $table, $availableFields, Request $request)
	{
		// $table, array $fields, $startIndex = 0, $count = 16, $sortBy = null, $sortOrder = null, Condition $con = null

		return $connector->getAll($table, $fields);
	}
}
