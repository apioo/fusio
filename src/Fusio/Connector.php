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

namespace Fusio;

use Doctrine\DBAL\Connection;
use PSX\Dependency\ObjectBuilderInterface;

/**
 * Connector
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://phpsx.org
 */
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
