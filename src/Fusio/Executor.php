<?php

namespace Fusio;

use Doctrine\DBAL\Connection;
use PSX\Dependency\ObjectBuilderInterface;

class Executor
{
	protected $connection;
	protected $factory;

	public function __construct(Connection $connection, Factory\Action $factory)
	{
		$this->connection = $connection;
		$this->factory    = $factory;
	}

	public function execute($actionId, Parameters $parameters, Body $data)
	{
		$action = $this->connection->fetchAssoc('SELECT class, config FROM fusio_action WHERE id = :id', array('id' => $actionId));

		if(empty($action))
		{
			throw new ConfigurationException('Invalid action');
		}

		$config = !empty($action['config']) ? unserialize($action['config']) : array();

		return $this->factory->factory($action['class'])->handle($parameters, $data, new Parameters($config));
	}
}
