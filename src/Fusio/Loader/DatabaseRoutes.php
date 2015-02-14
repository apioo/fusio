<?php

namespace Fusio\Loader;

use Doctrine\DBAL\Connection;
use PSX\Loader\RoutingParserInterface;

class DatabaseRoutes implements RoutingParserInterface
{
	protected $connection;

	protected $_collection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	public function getCollection()
	{
		if($this->_collection === null)
		{
			$collection = new RoutingCollection();
			$result     = $this->connection->fetchAll('SELECT id, methods, path, controller, config FROM fusio_routes WHERE path NOT LIKE "/backend/%"');

			foreach($result as $row)
			{
				$config = !empty($row['config']) ? unserialize($row['config']) : array();

				$collection->add(explode('|', $row['methods']), $row['path'], $row['controller'], $config);
			}

			$this->_collection = $collection;
		}

		return $this->_collection;
	}
}
