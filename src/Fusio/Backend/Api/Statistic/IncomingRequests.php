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

namespace Fusio\Backend\Api\Statistic;

use Fusio\Authorization\ProtectionTrait;
use Fusio\Backend\Table\Log;
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

    public function onGet()
    {
        $filter     = Log\QueryFilter::create($this->getParameters());
        $condition  = $filter->getCondition('log');
        $expression = $condition->getExpression($this->connection->getDatabasePlatform());

        // build data structure
        $fromDate = $filter->getFrom();
        $toDate   = $filter->getTo();
        $diff     = $toDate->getTimestamp() - $fromDate->getTimestamp();
        $data     = [];
        $labels   = [];

        while ($fromDate <= $toDate) {
            $data[$fromDate->format('Y-m-d')] = 0;
            $labels[] = $fromDate->format($diff < 2419200 ? 'D' : 'Y-m-d');

            $fromDate->add(new \DateInterval('P1D'));
        }

        // fill values
        $sql = '  SELECT COUNT(log.id) AS count,
                         DATE(log.date) AS date
                    FROM fusio_log log
                   WHERE ' . $expression . '
                GROUP BY DATE(log.date)';

        $result = $this->connection->fetchAll($sql, $condition->getValues());

        foreach ($result as $row) {
            $data[$row['date']] = (int) $row['count'];
        }

        $this->setBody(array(
            'labels' => $labels,
            'data'   => [array_values($data)],
            'series' => ['Requests'],
        ));
    }
}
