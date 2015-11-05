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

namespace Fusio\Impl\Backend\Api\Statistic;

use Fusio\Impl\Authorization\ProtectionTrait;
use Fusio\Impl\Backend\Table\Log;
use PSX\Controller\ApiAbstract;

/**
 * MostUsedApps
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class MostUsedApps extends ApiAbstract
{
    use ProtectionTrait;

    public function onGet()
    {
        $filter     = Log\QueryFilter::create($this->getParameters());
        $condition  = $filter->getCondition('log');
        $expression = $condition->getExpression($this->connection->getDatabasePlatform());

        // get the most used apps and build data structure
        $sql = '  SELECT log.appId
                    FROM fusio_log log
                   WHERE ' . $expression . '
                     AND log.appId IS NOT NULL
                GROUP BY log.appId
                ORDER BY COUNT(log.appId) DESC
                   LIMIT 6';

        $result = $this->connection->fetchAll($sql, $condition->getValues());
        $appIds = array();
        $data   = [];
        $series = [];

        foreach ($result as $row) {
            $appIds[] = $row['appId'];

            $data[$row['appId']] = [];
            $series[$row['appId']] = null;

            $fromDate = clone $filter->getFrom();
            $toDate   = clone $filter->getTo();
            while ($fromDate <= $toDate) {
                $data[$row['appId']][$fromDate->format('Y-m-d')] = 0;

                $fromDate->add(new \DateInterval('P1D'));
            }
        }

        if (!empty($appIds)) {
            $condition->in('log.appId', $appIds);
        }

        // fill data with values
        $expression = $condition->getExpression($this->connection->getDatabasePlatform());

        $sql = '    SELECT COUNT(log.id) AS count,
                           log.appId,
                           app.name,
                           DATE(log.date) AS date
                      FROM fusio_log log
                INNER JOIN fusio_app app
                        ON log.appId = app.id
                     WHERE ' . $expression . '
                  GROUP BY DATE(log.date), log.appId';

        $result = $this->connection->fetchAll($sql, $condition->getValues());

        foreach ($result as $row) {
            $series[$row['appId']] = $row['name'];
            $data[$row['appId']][$row['date']] = (int) $row['count'];
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
}
