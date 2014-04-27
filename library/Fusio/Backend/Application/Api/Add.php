<?php

namespace Fusio\Backend\Application\Api;

use Fusio\Controller\BackendController;
use Fusio\Entity\Connection;
use Fusio\Entity\Action;
use Fusio\Entity\Trigger;

class Add extends BackendController
{
	public function getParameters()
	{
		$actionId = $this->getUriFragments('action_id');
		$action   = $this->getAction($actionId);

		return $this->setBody(array(
			'parameters' => $this->getActionFactory()->getAction($action->getClass())->getParameters(),
		));
	}

	public function getTables()
	{
		$connectionId = $this->getUriFragments('connection_id');
		$connection   = $this->getConnection($connectionId);

		$this->setBody(array(
			'tables' => $connection->getConnector()->getTables()
		));
	}

	public function getFields()
	{
		$connectionId = $this->getUriFragments('connection_id');
		$table        = $this->getUriFragments('table');
		$connection   = $this->getConnection($connectionId);

		$this->setBody(array(
			'fields' => $connection->getConnector()->getFields($table)
		));
	}

	protected function getConnection($connectionId)
	{
		$connection = $this->getEntityManager()
			->getRepository('Fusio\Entity\Connection')
			->find($connectionId);

		if ($connection instanceof Connection) {
			return $connection;
		} else {
			throw new \Exception('Invalid connection');
		}
	}

	protected function getAction($actionId)
	{
		$action = $this->getEntityManager()
			->getRepository('Fusio\Entity\Action')
			->find($actionId);

		if ($action instanceof Action) {
			return $action;
		} else {
			throw new \Exception('Invalid action');
		}
	}

	protected function getTrigger($triggerId)
	{
		$trigger = $this->getEntityManager()
			->getRepository('Fusio\Entity\Trigger')
			->find($triggerId);

		if ($trigger instanceof Trigger) {
			return $trigger;
		} else {
			throw new \Exception('Invalid trigger');
		}
	}
}
