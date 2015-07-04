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
use Fusio\Context;

/**
 * RateLimit
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class RateLimit
{
    protected $connection;
    protected $context;

    public function __construct(Connection $connection, Context $context)
    {
        $this->connection = $connection;
        $this->context    = $context;
    }

    public function getRequestsPerMonth()
    {
        $sql = 'SELECT COUNT(id)
				  FROM fusio_log
				 WHERE appId = :appId
				   AND %s = %s';

        $sql = sprintf($sql,
            $this->connection->getDatabasePlatform()->getSubstringExpression($this->connection->getDatabasePlatform()->getNowExpression(), 0, 7),
            $this->connection->getDatabasePlatform()->getSubstringExpression('date', 0, 7));

        return $this->connection->fetchColumn($sql, array(
            'appId' => $this->context->getApp()->getId(),
        ));
    }

    public function getRequestsPerDay()
    {
        $sql = 'SELECT COUNT(id)
				  FROM fusio_log
				 WHERE appId = :appId
				   AND %s = %s';

        $sql = sprintf($sql,
            $this->connection->getDatabasePlatform()->getSubstringExpression($this->connection->getDatabasePlatform()->getNowExpression(), 0, 10),
            $this->connection->getDatabasePlatform()->getSubstringExpression('date', 0, 10));

        return $this->connection->fetchColumn($sql, array(
            'appId' => $this->context->getApp()->getId(),
        ));
    }

    public function getRequestsOfRoutePerMonth()
    {
        $sql = 'SELECT COUNT(id)
				  FROM fusio_log
				 WHERE appId = :appId
				   AND routeId = :routeId
				   AND %s = %s';

        $sql = sprintf($sql,
            $this->connection->getDatabasePlatform()->getSubstringExpression($this->connection->getDatabasePlatform()->getNowExpression(), 0, 7),
            $this->connection->getDatabasePlatform()->getSubstringExpression('date', 0, 7));

        return $this->connection->fetchColumn($sql, array(
            'appId'   => $this->context->getApp()->getId(),
            'routeId' => $this->context->getRouteId(),
        ));
    }

    public function getRequestsOfRoutePerDay()
    {
        $sql = 'SELECT COUNT(id)
				  FROM fusio_log
				 WHERE appId = :appId
				   AND routeId = :routeId
				   AND %s = %s';

        $sql = sprintf($sql,
            $this->connection->getDatabasePlatform()->getSubstringExpression($this->connection->getDatabasePlatform()->getNowExpression(), 0, 10),
            $this->connection->getDatabasePlatform()->getSubstringExpression('date', 0, 10));

        return $this->connection->fetchColumn($sql, array(
            'appId'   => $this->context->getApp()->getId(),
            'routeId' => $this->context->getRouteId(),
        ));
    }
}
