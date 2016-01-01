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

use Doctrine\DBAL\Connection as DBALConnection;
use Fusio\Impl\Authorization\TokenGenerator;
use Fusio\Impl\Backend\Table\Connection as TableConnection;
use PSX\Data\ResultSet;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;

/**
 * Dashboard
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Dashboard
{
    protected $connection;

    public function __construct(DBALConnection $connection)
    {
        $this->connection = $connection;
    }

    public function getLatestApps()
    {
        $sql = '  SELECT name,
                         date
                    FROM fusio_app
                ORDER BY date DESC
                   LIMIT 6';

        return $this->connection->fetchAll($sql);
    }

    public function getLatestRequests()
    {
        $sql = '  SELECT path,
                         ip,
                         date
                    FROM fusio_log
                ORDER BY date DESC
                   LIMIT 6';

        return $this->connection->fetchAll($sql);
    }
}
