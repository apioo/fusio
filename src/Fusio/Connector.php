<?php

namespace Fusio;

use Doctrine\DBAL\Connection;
use PSX\Dependency\ObjectBuilderInterface;

class Connector
{
	protected $connection;
	protected $factory;

	public function __construct(Connection $connection, Factory\Connection $factory)
	{
		$this->connection = $connection;
		$this->factory    = $factory;
	}

	/**
	 * Returns an arbitrary connection to a system which was previously 
	 * configured by the user
	 *
	 * @param integer $connectionId
	 * @return Fusio\ConnectionInterface
	 */
	public function getConnection($connectionId)
	{
		$connection = $this->connection->fetchAssoc('SELECT class, config FROM fusio_connection WHERE id = :id', array('id' => $connectionId));

		if(empty($connection))
		{
			throw new ConfigurationException('Invalid connection');
		}

		$config = !empty($connection['config']) ? unserialize($connection['config']) : array();

		return $this->factory->factory($connection['class'])->getConnection(new Parameters($config));
	}
}
