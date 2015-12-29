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

namespace Fusio\Impl\Backend\Table\Routes;

use PSX\Sql\TableAbstract;

/**
 * Schema
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Schema extends TableAbstract
{
    const STATUS_REQUIRED = 1;
    const STATUS_OPTIONAL = 0;

    public function getName()
    {
        return 'fusio_routes_schema';
    }

    public function getColumns()
    {
        return array(
            'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
            'routeId' => self::TYPE_INT,
            'schemaId' => self::TYPE_INT,
            'status' => self::TYPE_INT,
        );
    }

    public function deleteAllFromRoute($routeId)
    {
        $sql = 'DELETE FROM fusio_routes_schema
                      WHERE routeId = :id';

        $this->connection->executeQuery($sql, ['id' => $routeId]);
    }

    public function deleteBySchema($schemaId)
    {
        $sql = 'DELETE FROM fusio_routes_schema
                      WHERE schemaId = :id';

        $this->connection->executeQuery($sql, ['id' => $schemaId]);
    }

    public function getDependingRoutePaths($schemaId)
    {
        $sql = '    SELECT routes.path
                      FROM fusio_routes_schema schem
                INNER JOIN fusio_routes routes
                        ON routes.id = schem.routeId
                     WHERE schem.schemaId = :id';

        $result = $this->connection->fetchAll($sql, ['id' => $schemaId]);
        $paths  = [];

        foreach ($result as $row) {
            $paths[] = $row['path'];
        }

        return $paths;
    }
}
