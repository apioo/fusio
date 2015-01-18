<?php

namespace Fusio\Loader\RoutingParser;

use Doctrine\DBAL\Connection;
use PSX\Loader\RoutingCollection;
use PSX\Loader\RoutingParserInterface;

class Table implements RoutingParserInterface
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
			$result     = $this->connection->fetchAll('SELECT methods, path, controller FROM fusio_routes');

			foreach($result as $row)
			{
				$allowed = explode('|', $row['methods']);
				$path    = $row['path'];
				$class   = $row['controller'];

				if(!empty($allowed) && !empty($path) && !empty($class))
				{
					$collection->add($allowed, $path, $class);
				}
			}

			$this->_collection = $collection;
		}

		return $this->_collection;
	}
}
