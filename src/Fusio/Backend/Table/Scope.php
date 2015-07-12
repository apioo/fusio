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

namespace Fusio\Backend\Table;

use PSX\Sql\Condition;
use PSX\Sql\TableAbstract;

/**
 * Scope
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Scope extends TableAbstract
{
    public function getName()
    {
        return 'fusio_scope';
    }

    public function getColumns()
    {
        return array(
            'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
            'name' => self::TYPE_VARCHAR,
        );
    }

    public function getByUser($userId)
    {
        $sql = '    SELECT scope.name
				      FROM fusio_user_scope userScope
				INNER JOIN fusio_scope scope
				        ON scope.id = userScope.scopeId
				     WHERE userScope.userId = :userId';

        $result = $this->connection->fetchAll($sql, array('userId' => $userId)) ?: array();
        $names  = array();

        foreach ($result as $row) {
            $names[] = $row['name'];
        }

        return $names;
    }

    public function getByApp($appId)
    {
        $sql = '    SELECT scope.name
				      FROM fusio_app_scope appScope
				INNER JOIN fusio_scope scope
				        ON scope.id = appScope.scopeId
				     WHERE appScope.appId = :appId';

        $result = $this->connection->fetchAll($sql, array('appId' => $appId)) ?: array();
        $names  = array();

        foreach ($result as $row) {
            $names[] = $row['name'];
        }

        return $names;
    }

    public function getByNames(array $names)
    {
        $names = array_filter($names);

        if (!empty($names)) {
            return $this->getAll(0, 1024, null, null, new Condition(['name', 'IN', $names]));
        } else {
            return array();
        }
    }
}
