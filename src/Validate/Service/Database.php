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

namespace Fusio\Impl\Validate\Service;

use Doctrine\DBAL\Connection;
use Fusio\Impl\Connector;
use RuntimeException;

/**
 * Database
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Database
{
    protected $connector;

    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    public function rowExists($connectionId, $table, $column, $value)
    {
        if (!preg_match('/^[A-z0-9\_]{1,64}$/', $table)) {
            throw new RuntimeException('Table name "' . $table . '" contains invalid characters');
        }

        if (!preg_match('/^[A-z0-9\_]{1,64}$/', $column)) {
            throw new RuntimeException('Column name "' . $column . '" contains invalid characters');
        }

        $connection = $this->connector->getConnection($connectionId);
        if ($connection instanceof Connection) {
            $queryBuilder = $connection->createQueryBuilder();

            $queryBuilder
                ->select($connection->getDatabasePlatform()->getCountExpression($column))
                ->from($table)
                ->where($column . ' = ?');

            $count = (int) $connection->fetchColumn($queryBuilder->getSQL(), [$value]);

            return $count > 0;
        } else {
            // @TODO handle other connection types i.e. MongoDB
        }

        return false;
    }
}
