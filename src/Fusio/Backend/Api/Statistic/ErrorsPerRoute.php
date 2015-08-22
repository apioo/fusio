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
 * ErrorsPerRoute
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ErrorsPerRoute extends ApiAbstract
{
    use ProtectionTrait;

    public function onGet()
    {
        $filter     = Log\QueryFilter::create($this->getParameters());
        $condition  = $filter->getCondition('log');
        $expression = $condition->getExpression($this->connection->getDatabasePlatform());

        // get the most used routes and build data structure
        $sql = '    SELECT log.routeId
                      FROM fusio_log_error error
                INNER JOIN fusio_log log
                        ON log.id = error.logId
                     WHERE ' . $expression . '
                       AND log.routeId IS NOT NULL
                  GROUP BY log.routeId
                  ORDER BY COUNT(error.id) DESC
                     LIMIT 6';

        $result   = $this->connection->fetchAll($sql, $condition->getValues());
        $routeIds = array();
        $data     = [];
        $series   = [];

        foreach ($result as $row) {
            $routeIds[] = $row['routeId'];

            $data[$row['routeId']] = [];
            $series[$row['routeId']] = null;

            $fromDate = clone $filter->getFrom();
            $toDate   = clone $filter->getTo();
            while ($fromDate <= $toDate) {
                $data[$row['routeId']][$fromDate->format('Y-m-d')] = 0;

                $fromDate->add(new \DateInterval('P1D'));
            }
        }

        if (!empty($routeIds)) {
            $condition->in('log.routeId', $routeIds);
        }

        // fill data with values
        $expression = $condition->getExpression($this->connection->getDatabasePlatform());

        $sql = '    SELECT COUNT(error.id) AS count,
                           log.routeId,
                           routes.path,
                           DATE(log.date) AS date
                      FROM fusio_log_error error
                INNER JOIN fusio_log log
                        ON log.id = error.logId
                INNER JOIN fusio_routes routes
                        ON log.routeId = routes.id
                     WHERE ' . $expression . '
                  GROUP BY DATE(log.date), log.routeId';

        $result = $this->connection->fetchAll($sql, $condition->getValues());

        foreach ($result as $row) {
            $series[$row['routeId']] = $row['path'];
            $data[$row['routeId']][$row['date']] = (int) $row['count'];
        }

        // build labels
        $fromDate = clone $filter->getFrom();
        $toDate   = clone $filter->getTo();
        $diff     = $toDate->getTimestamp() - $fromDate->getTimestamp();
        $labels   = [];
        while ($fromDate <= $toDate) {
            $labels[] = $fromDate->format($diff < 2419200 ? 'D' : 'Y-m-d');

            $fromDate->add(new \DateInterval('P1D'));
        }

        // clean data structure
        $values = [];
        foreach ($data as $row) {
            $values[] = array_values($row);
        }

        $this->setBody(array(
            'labels' => $labels,
            'data'   => array_values($values),
            'series' => array_values($series),
        ));
    }

/*
    public function onGet()
    {
        $filter     = Log\QueryFilter::create($this->getParameters());
        $condition  = $filter->getCondition('log');
        $expression = $condition->getExpression($this->connection->getDatabasePlatform());

        // build data structure
        $fromDate = $filter->getFrom();
        $toDate   = $filter->getTo();
        $values   = [];

        while ($fromDate <= $toDate) {
            $values[$fromDate->format('Y-m-d')] = 0;

            $fromDate->add(new \DateInterval('P1D'));
        }

        // fill values
        $sql = '    SELECT COUNT(error.id) AS count,
                           DATE(log.date) AS date
                      FROM fusio_log_error error
                INNER JOIN fusio_log log
                        ON log.id = error.logId
                     WHERE ' . $expression . '
                  GROUP BY DATE(log.date)';

        $result = $this->connection->fetchAll($sql, $condition->getValues());

        foreach ($result as $row) {
            $values[$row['date']] = (int) $row['count'];
        }

        $this->setBody(array(
            'labels' => array_keys($values),
            'data'   => [array_values($values)],
            'series' => ['Errors'],
        ));
    }
    */
}
