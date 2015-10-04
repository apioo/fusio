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

namespace Fusio\Template\Filter;

use Doctrine\DBAL\Connection;
use PSX\Data\RecordInterface;
use PSX\Http\Exception as StatusCode;
use RuntimeException;

/**
 * RowExists
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class RowExists
{
    const FILTER_NAME = 'row_exists';

    protected $connection;

    public function __invoke($value, $table, $column)
    {
        if ($this->connection instanceof Connection) {
            if (!preg_match('/^[A-z0-9\_]{1,64}$/', $table)) {
                throw new RuntimeException('Table name "' . $table . '" contains invalid characters');
            }

            if (!preg_match('/^[A-z0-9\_]{1,64}$/', $column)) {
                throw new RuntimeException('Column name "' . $column . '" contains invalid characters');
            }

            $result = $this->connection->fetchColumn('SELECT ' . $column . ' FROM ' . $table . ' WHERE ' . $column . ' = ?', [$value]);
            if (empty($result)) {
                throw new StatusCode\NotFoundException('Entry ' . $value . ' does not exist');
            }
        }

        return $value;
    }

    public function setConnection($connection)
    {
        return $this->connection = $connection;
    }
}
