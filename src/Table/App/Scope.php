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

namespace Fusio\Impl\Table\App;

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
        return 'fusio_app_scope';
    }

    public function getColumns()
    {
        return array(
            'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
            'appId' => self::TYPE_INT,
            'scopeId' => self::TYPE_INT,
        );
    }

    public function deleteAllFromApp($appId)
    {
        $sql = 'DELETE FROM fusio_app_scope
				      WHERE appId = :appId';

        $this->connection->executeQuery($sql, array('appId' => $appId));
    }

    public function getByApp($appId, $scope, array $exclude = array())
    {
        $sql = '    SELECT scope.name,
                           scope.description
                      FROM fusio_app_scope appScope
                INNER JOIN fusio_scope scope
                        ON scope.id = appScope.scopeId
                     WHERE appScope.appId = :appId
                  ORDER BY scope.id ASC';

        $result = $this->connection->fetchAll($sql, array('appId' => $appId)) ?: array();
        $data   = array();
        $scopes = explode(',', $scope);

        foreach ($result as $availableScope) {
            if (in_array($availableScope['name'], $scopes)) {
                // is the scope excluded
                if (in_array($availableScope['name'], $exclude)) {
                    continue;
                }

                $data[] = $availableScope;
            }
        }

        return $data;
    }

    public function getValidScopes($appId, array $scopes, array $exclude = array())
    {
        $sql = '    SELECT scope.name
                      FROM fusio_app_scope appScope
                INNER JOIN fusio_scope scope
                        ON scope.id = appScope.scopeId
                     WHERE appScope.appId = :appId
                  ORDER BY scope.id ASC';

        $result = $this->connection->fetchAll($sql, array('appId' => $appId));
        $data   = array();

        foreach ($result as $availableScope) {
            if (in_array($availableScope['name'], $scopes)) {
                // is the scope excluded
                if (in_array($availableScope['name'], $exclude)) {
                    continue;
                }

                $data[] = $availableScope['name'];
            }
        }

        return $data;
    }
}
