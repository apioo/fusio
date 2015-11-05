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

namespace Fusio\Impl;

use Doctrine\DBAL\Connection;
use Fusio\Engine\ConnectorInterface;

/**
 * Connector
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Connector implements ConnectorInterface
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
     * @return \Fusio\Engine\ConnectionInterface
     */
    public function getConnection($connectionId)
    {
        if (is_numeric($connectionId)) {
            $column = 'id';
        } else {
            $column = 'name';
        }

        $connection = $this->connection->fetchAssoc('SELECT class, config FROM fusio_connection WHERE ' . $column . ' = :id', array('id' => $connectionId));

        if (empty($connection)) {
            throw new ConfigurationException('Invalid connection');
        }

        $config = !empty($connection['config']) ? unserialize($connection['config']) : array();

        return $this->factory->factory($connection['class'])->getConnection(new Parameters($config));
    }
}
