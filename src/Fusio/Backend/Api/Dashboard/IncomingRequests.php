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

use DateInterval;
use DateTime;
use Fusio\Authorization\ProtectionTrait;
use PSX\Controller\ApiAbstract;

/**
 * IncomingRequests
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class IncomingRequests extends ApiAbstract
{
    use ProtectionTrait;

    const PAST_DAYS = 9;

    public function onGet()
    {
        $past = new DateTime();
        $past->sub(new DateInterval('P' . self::PAST_DAYS. 'D'));

        $labels = array();
        $data   = array();

        for ($i = 0; $i <= self::PAST_DAYS; $i++) {
            $sql = 'SELECT COUNT(id) as count
					  FROM fusio_log
					 WHERE DATE(date) = :date';

            $count = $this->connection->fetchColumn($sql, array('date' => $past->format('Y-m-d')));

            $data[]   = (int) $count;
            $labels[] = $past->format('d.m');

            $past->add(new DateInterval('P1D'));
        }

        $this->setBody(array(
            'labels' => $labels,
            'data'   => [$data],
            'series' => ['Requests'],
        ));
    }
}
