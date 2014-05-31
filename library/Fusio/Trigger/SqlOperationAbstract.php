<?php

namespace Fusio\Trigger;

use Fusio\Parameter;
use Fusio\TriggerAbstract;
use PSX\Http\Request;

abstract class SqlOperationAbstract extends TriggerAbstract
{
	public function getParameters()
	{
		return array(
			new Parameter\Choice('connection_id', 'Connection', $this->getConnections()),
			new Parameter\Text('table', 'Table'),
		);
	}

	protected function getConnections()
	{
		$result = $this->container->get('entity_manager')->getRepository('Fusio\Entity\Connection')->findAll();
		$connections = array();

		foreach($result as $connection)
		{
			$connections[$connection->getId()] = $connection->getName();
		}

		return $connections;
	}
}
