<?php

namespace Fusio;

use Doctrine\DBAL\Connection;
use PSX\Dependency\ObjectBuilderInterface;

class ActionExecutor
{
	protected $connection;
	protected $actionFactory;

	public function __construct(Connection $connection, ActionFactory $actionFactory)
	{
		$this->connection    = $connection;
		$this->actionFactory = $actionFactory;
	}

	public function execute($actionId, Parameters $parameters, Body $data)
	{
		$action = $this->connection->fetchAssoc('SELECT class, config FROM fusio_action WHERE id = :id', array('id' => $actionId));

		if(empty($action))
		{
			throw new ConfigurationException('Invalid action');
		}

		$config = !empty($action['config']) ? unserialize($action['config']) : array();

		return $this->actionFactory->getAction($action['class'])->handle($parameters, $data, new Parameters($config));
	}
}
