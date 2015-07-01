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

namespace Fusio\Backend\Api\Dashboard;

use DateTime;
use DateInterval;
use Fusio\Authorization\ProtectionTrait;
use PSX\Controller\ApiAbstract;

/**
 * MostUsedApps
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class MostUsedApps extends ApiAbstract
{
    use ProtectionTrait;

    public function onGet()
    {
        $sql = '    SELECT COUNT(log.id) AS count,
				           app.name
				      FROM fusio_log log
				INNER JOIN fusio_app app
				        ON log.appId = app.id
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
