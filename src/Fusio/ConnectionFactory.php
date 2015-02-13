<?php

namespace Fusio;

use Doctrine\DBAL\Connection;
use PSX\Dependency\ObjectBuilderInterface;

class ConnectionFactory
{
	protected $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Returns an arbitrary connection to a system which was previously 
	 * configured by the user
	 *
	 * @param integer $connectionId
	 * @return Fusio\ConnectionInterface
	 */
	public function getById($connectionId)
	{
		$connection = $this->connection->fetchAssoc('SELECT type, parameters FROM fusio_connection WHERE id = :id', array('id' => $connectionId));

		if(empty($connection))
		{
			throw new \RuntimeException('Connection not available');
		}

		$parameters = $connection['parameters'];
		if(!empty($parameters))
		{
			$parameters = unserialize($parameters);
		}
		else
		{
			$parameters = array();
		}

		$factory = null;
		switch($connection['type'])
		{
			case 'NATIVE':
				return $this->connection;
				break;

			case 'DBAL':
				$factory = new Connection\DBAL();
				break;

			case 'MONGODB':
				$factory = new Connection\MongoDB();
				break;

			default:
				throw new \RuntimeException('Invalid connection type');
				break;
		}

		return $factory->getConnection(new Parameters($parameters));
	}
}
