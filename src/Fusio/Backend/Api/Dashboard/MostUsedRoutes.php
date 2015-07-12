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

namespace Fusio\Backend\Api\Dashboard;

use Fusio\Authorization\ProtectionTrait;
use PSX\Controller\ApiAbstract;

/**
 * MostUsedRoutes
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class MostUsedRoutes extends ApiAbstract
{
    use ProtectionTrait;

    public function onGet()
    {
        $sql = '    SELECT COUNT(log.id) AS count,
				           routes.path
				      FROM fusio_log log
				INNER JOIN fusio_routes routes
				        ON log.routeId = routes.id
				     WHERE log.date > DATE_SUB(NOW(), INTERVAL 1 MONTH)
				  GROUP BY log.routeId
				  ORDER BY count DESC
				     LIMIT 6';

        $result = $this->connection->fetchAll($sql);

        $this->setBody(array(
            'entry' => $result,
        ));
    }
}
