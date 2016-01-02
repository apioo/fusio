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

namespace Fusio\Impl\Service;

use Fusio\Impl\Table\Connection as TableConnection;
use PSX\Data\ResultSet;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;

/**
 * Connection
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Connection
{
    protected $connectionTable;

    public function __construct(TableConnection $connectionTable)
    {
        $this->connectionTable = $connectionTable;
    }

    public function getAll($startIndex = 0, $search = null)
    {
        $condition = !empty($search) ? new Condition(['name', 'LIKE', '%' . $search . '%']) : null;

        $this->connectionTable->setRestrictedFields(['class', 'config']);

        return new ResultSet(
            $this->connectionTable->getCount($condition),
            $startIndex,
            16,
            $this->connectionTable->getAll($startIndex, 16, 'id', Sql::SORT_DESC, $condition)
        );
    }

    public function get($connectionId)
    {
        $connection = $this->connectionTable->get($connectionId);

        if (!empty($connection)) {
            return $connection;
        } else {
            throw new StatusCode\NotFoundException('Could not find connection');
        }
    }

    public function create($name, $class, $config)
    {
        $this->connectionTable->create(array(
            'name'   => $name,
            'class'  => $class,
            'config' => $config,
        ));
    }

    public function update($connectionId, $name, $class, $config)
    {
        $connection = $this->connectionTable->get($connectionId);

        if (!empty($connection)) {
            $this->connectionTable->update(array(
                'id'     => $connection->getId(),
                'name'   => $name,
                'class'  => $class,
                'config' => $config,
            ));
        } else {
            throw new StatusCode\NotFoundException('Could not find connection');
        }
    }

    public function delete($connectionId)
    {
        $connection = $this->connectionTable->get($connectionId);

        if (!empty($connection)) {
            $this->connectionTable->delete(array(
                'id' => $connection->getId()
            ));
        } else {
            throw new StatusCode\NotFoundException('Could not find connection');
        }
    }
}
