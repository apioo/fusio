<?php

namespace Fusio\Action;

use Fusio\Parameter;
use PSX\Http\Request;

class DeleteModel extends ModelActionAbstract
{
	public function execute(Request $request, array $parameters)
	{
		$modelId      = isset($parameters['model_id']) ? $parameters['model_id'] : null;
		$connectionId = isset($parameters['connection_id']) ? $parameters['connection_id'] : null;
		$table        = isset($parameters['table']) ? $parameters['table'] : null;

		// import record
		$record = $this->import($request, $modelId);

		// insert record
		return $this->deleteRecord($this->getConnector($connectionId), $table, $record);
	}

	public function getParameters()
	{
		return array(
			new Parameter\Text('model_id', 'Model'),
			new Parameter\Text('connection_id', 'Connection'),
			new Parameter\Text('table', 'Table'),
		);
	}

	protected function deleteRecord(ConnectorInterface $connector, $table, RecordInterface $record)
	{
		try {
			$connector->delete($table, $record);

			return array(
				'success' => true,
			);
		} catch(\Exception $e) {
			return array(
				'success' => false,
				'text' => $e->getMessage(),
			);
		}
	}
}
