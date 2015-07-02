<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\App;

use Doctrine\DBAL\Connection;
use Fusio\App;

/**
 * Loader
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Loader
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getById($appId)
    {
        $app = $this->newApp($appId);

        if (!empty($appId)) {
            $app->setAnonymous(false);
            $app->setScopes($this->getScopes($appId));
        } else {
            $app->setAnonymous(true);
            $app->setScopes(array());
        }

        return $app;
    }

    protected function newApp($appId)
    {
        if (empty($appId)) {
            return new App();
        }

        $sql = 'SELECT id,
				       userId,
				       status,
				       name,
				       url,
				       appKey
				  FROM fusio_app
				 WHERE id = :appId';

        $row = $this->connection->fetchAssoc($sql, array('appId' => $appId));

        if (!empty($row)) {
            $app = new App();
            $app->setId($row['id']);
            $app->setUserId($row['userId']);
            $app->setStatus($row['status']);
            $app->setName($row['name']);
            $app->setUrl($row['url']);
            $app->setAppKey($row['appKey']);

            return $app;
        } else {
            throw new \RuntimeException('Invalid app id');
        }
    }

    protected function getScopes($appId)
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
}
