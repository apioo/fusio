<?php

namespace Fusio\Action;

use Fusio\ActionAbstract;
use Fusio\Connection\Factory;
use Fusio\Entity\Connection;
use Fusio\Parameter;
use PSX\Http\Request;

abstract class ConnectionActionAbstract extends ActionAbstract
{
	protected function getConnector($connectionId)
	{
		$connection = $this->getEntityManager()
			->getRepository('Fusio\Entity\Connection')
			->find($connectionId);

		if ($connection instanceof Connection) {
			return Factory::factory($connection->getType())->getConnector($connection->getParams());
		} else {
			throw new InvalidArgumentException('Connection not available');
		}
	}
}
