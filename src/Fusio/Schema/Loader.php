<?php
/*
 * Fusio
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

namespace Fusio\Schema;

use Doctrine\DBAL\Connection;
use PSX\Data\SchemaInterface;
use RuntimeException;

/**
 * Loader
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Loader
{
	protected $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	public function getSchema($schemaId)
	{
		$row  = $this->connection->fetchAssoc('SELECT name, cache FROM fusio_schema WHERE id LIKE :id', array('id' => $schemaId));

		if(!empty($row))
		{
			$cache = isset($row['cache']) ? $row['cache'] : null;

			if(!empty($cache))
			{
				$cache = unserialize($cache);

				if($cache instanceof SchemaInterface)
				{
					return $cache;
				}
			}

			throw new RuntimeException(sprintf('Schema %s cache not available', $row['name']));
		}
		else
		{
			throw new RuntimeException('Invalid schema reference ' . $schemaId);
		}
	}
}
