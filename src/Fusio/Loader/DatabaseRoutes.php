<?php
/*
 * fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Loader;

use Doctrine\DBAL\Connection;
use PSX\Loader\RoutingParserInterface;

/**
 * DatabaseRoutes
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://phpsx.org
 */
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
