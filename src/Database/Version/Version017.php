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

namespace Fusio\Impl\Database\Version;

use Doctrine\DBAL\Connection;

/**
 * Version017
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Version017 extends Version010
{
    public function executeUpgrade(Connection $connection)
    {
        // update action class names
        $actions = [
            'Fusio\Action\BeanstalkPush' => 'Fusio\Impl\Action\MqBeanstalk',
            'Fusio\Action\CacheResponse' => 'Fusio\Impl\Action\CacheResponse',
            'Fusio\Action\Composite' => 'Fusio\Impl\Action\Composite',
            'Fusio\Action\Condition' => 'Fusio\Impl\Action\Condition',
            'Fusio\Action\HttpRequest' => 'Fusio\Impl\Action\HttpRequest',
            'Fusio\Action\Pipe' => 'Fusio\Action\Pipe',
            'Fusio\Action\RabbitMqPush' => 'Fusio\Impl\Action\MqAmqp',
            'Fusio\Action\SqlExecute' => 'Fusio\Impl\Action\SqlExecute',
            'Fusio\Action\SqlFetchAll' => 'Fusio\Impl\Action\SqlFetchAll',
            'Fusio\Action\SqlFetchRow' => 'Fusio\Impl\Action\SqlFetchRow',
            'Fusio\Action\StaticResponse' => 'Fusio\Impl\Action\StaticResponse',
        ];

        foreach ($actions as $oldClass => $newClass) {
            $connection->executeUpdate('UPDATE fusio_action SET class = :new_class WHERE class = :old_class', [
                'new_class' => $newClass,
                'old_class' => $oldClass,
            ]);
        }

        // update connection class names
        $actions = [
            'Fusio\Connection\Beanstalk' => 'Fusio\Impl\Connection\Beanstalk',
            'Fusio\Connection\DBAL' => 'Fusio\Impl\Connection\DBAL',
            'Fusio\Connection\DBALAdvanced' => 'Fusio\Impl\Connection\DBALAdvanced',
            'Fusio\Connection\MongoDB' => 'Fusio\Impl\Connection\MongoDB',
            'Fusio\Connection\Native' => 'Fusio\Impl\Connection\Native',
            'Fusio\Connection\RabbitMQ' => 'Fusio\Impl\Connection\RabbitMQ',
        ];

        foreach ($actions as $oldClass => $newClass) {
            $connection->executeUpdate('UPDATE fusio_connection SET class = :new_class WHERE class = :old_class', [
                'new_class' => $newClass,
                'old_class' => $oldClass,
            ]);
        }

        // update routes class names
        $routes = $connection->fetchAll('SELECT id, controller FROM fusio_routes');
        foreach ($routes as $route) {
            if (substr($route['controller'], 0, 6) == 'Fusio\\' && substr($route['controller'], 0, 11) != 'Fusio\\Impl\\') {
                $newController = 'Fusio\\Impl\\' . substr($route['controller'], 6);
                $connection->executeUpdate('UPDATE fusio_routes SET controller = :controller WHERE id = :id', [
                    'controller' => $newController,
                    'id'         => $route['id'],
                ]);
            }
        }

        // insert new classes table
        $data = $this->getInstallInserts();

        if (isset($data['fusio_connection_class'])) {
            foreach ($data['fusio_connection_class'] as $row) {
                $connection->insert('fusio_connection_class', $row);
            }
        }

        if (isset($data['fusio_action_class'])) {
            foreach ($data['fusio_action_class'] as $row) {
                $connection->insert('fusio_action_class', $row);
            }
        }
    }
}
