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

namespace Fusio\Database\Version;

use Doctrine\DBAL\Connection;

/**
 * Version014
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Version014 extends Version010
{
    public function executeUpgrade(Connection $connection)
    {
        // replace dashboard with statistic routes
        $routes = [
            '/backend/dashboard/incoming_requests' => [
                'path'       => '/backend/statistic/incoming_requests',
                'controller' => 'Fusio\Backend\Api\Statistic\IncomingRequests',
            ],
            '/backend/dashboard/most_used_routes' => [
                'path'       => '/backend/statistic/most_used_routes',
                'controller' => 'Fusio\Backend\Api\Statistic\MostUsedRoutes',
            ],
            '/backend/dashboard/most_used_apps' => [
                'path'       => '/backend/statistic/most_used_apps',
                'controller' => 'Fusio\Backend\Api\Statistic\MostUsedApps',
            ],
            '/backend/dashboard/latest_users' => [
                'path'       => '/backend/statistic/errors_per_route',
                'controller' => 'Fusio\Backend\Api\Statistic\ErrorsPerRoute',
            ],
        ];

        foreach ($routes as $oldRoute => $row) {
            $connection->update('fusio_routes', $row, ['path' => $oldRoute]);
        }
    }
}
