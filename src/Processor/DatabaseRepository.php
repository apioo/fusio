<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace Fusio\Impl\Processor;

use Doctrine\DBAL\Connection;
use Fusio\Impl\Model\Action;

/**
 * DatabaseRepository
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class DatabaseRepository implements RepositoryInterface
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAction($actionId)
    {
        if (is_numeric($actionId)) {
            $column = 'id';
        } else {
            $column = 'name';
        }

        $sql = 'SELECT id,
                       name,
                       class,
                       config,
                       date
                  FROM fusio_action
                 WHERE ' . $column . ' = :id';

        $row = $this->connection->fetchAssoc($sql, array('id' => $actionId));

        if (!empty($row)) {
            $config = !empty($row['config']) ? unserialize($row['config']) : array();

            $action = new Action();
            $action->setId($row['id']);
            $action->setName($row['name']);
            $action->setClass($row['class']);
            $action->setConfig($config);
            $action->setDate($row['date']);

            return $action;
        } else {
            return null;
        }
    }
}
