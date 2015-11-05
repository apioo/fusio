<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Impl\Loader;

use Doctrine\DBAL\Connection;
use PSX\Loader\RoutingParserInterface;

/**
 * DatabaseRoutes
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
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
        if ($this->_collection === null) {
            $collection = new RoutingCollection();
            $result     = $this->connection->fetchAll('SELECT id, methods, path, controller, config FROM fusio_routes WHERE status = 1');

            foreach ($result as $row) {
                $config = !empty($row['config']) ? unserialize($row['config']) : array();

                $collection->add(explode('|', $row['methods']), $row['path'], $row['controller'], $config);
            }

            $this->_collection = $collection;
        }

        return $this->_collection;
    }
}
