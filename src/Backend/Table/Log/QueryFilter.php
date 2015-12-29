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

namespace Fusio\Impl\Backend\Table\Log;

use PSX\Sql\Condition;

/**
 * QueryFilter
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class QueryFilter
{
    /**
     * @var \DateTime
     */
    protected $from;

    /**
     * @var \DateTime
     */
    protected $to;

    /**
     * @var integer
     */
    protected $appId;

    /**
     * @var integer
     */
    protected $routeId;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $header;

    /**
     * @var string
     */
    protected $body;

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getRouteId()
    {
        return $this->routeId;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getCondition($alias = null)
    {
        $alias     = $alias !== null ? $alias . '.' : '';
        $condition = new Condition();
        $condition->greaterThen($alias . 'date', $this->from->format('Y-m-d 00:00:00'));
        $condition->lowerThen($alias . 'date', $this->to->format('Y-m-d 23:59:59'));

        if (!empty($this->appId)) {
            $condition->equals($alias . 'appId', $this->appId);
        }

        if (!empty($this->routeId)) {
            $condition->equals($alias . 'routeId', $this->routeId);
        }

        if (!empty($this->ip)) {
            $condition->like($alias . 'ip', $this->ip);
        }

        if (!empty($this->userAgent)) {
            $condition->like($alias . 'userAgent', '%' . $this->userAgent . '%');
        }

        if (!empty($this->method)) {
            $condition->equals($alias . 'method', $this->method);
        }

        if (!empty($this->path)) {
            $condition->like($alias . 'path', $this->path . '%');
        }

        if (!empty($this->header)) {
            $condition->like($alias . 'header', '%' . $this->header . '%');
        }

        if (!empty($this->body)) {
            $condition->like($alias . 'body', '%' . $this->body . '%');
        }

        return $condition;
    }

    public static function create(array $parameters)
    {
        $from      = isset($parameters['from']) ? $parameters['from'] : '-1 month';
        $to        = isset($parameters['to']) ? $parameters['to'] : 'now';
        $appId     = isset($parameters['appId']) ? $parameters['appId'] : null;
        $routeId   = isset($parameters['routeId']) ? $parameters['routeId'] : null;
        $ip        = isset($parameters['ip']) ? $parameters['ip'] : null;
        $userAgent = isset($parameters['userAgent']) ? $parameters['userAgent'] : null;
        $method    = isset($parameters['method']) ? $parameters['method'] : null;
        $path      = isset($parameters['path']) ? $parameters['path'] : null;
        $header    = isset($parameters['header']) ? $parameters['header'] : null;
        $body      = isset($parameters['body']) ? $parameters['body'] : null;
        $search    = isset($parameters['search']) ? $parameters['search'] : null;

        $from = new \DateTime($from);
        $to   = new \DateTime($to);

        // from date is large then to date
        if ($from->getTimestamp() > $to->getTimestamp()) {
            $tmp  = clone $from;
            $from = $to;
            $to   = $tmp;
        }

        // check if diff between from and to is larger then ca 2 months
        if (($to->getTimestamp() - $from->getTimestamp()) > 4838400) {
            $to = clone $from;
            $to->add(new \DateInterval('P2M'));
        }

        // parse search if available
        if (!empty($search)) {
            $parts = explode(',', $search);
            foreach ($parts as $part) {
                $part = trim($part);
                if (filter_var($part, FILTER_VALIDATE_IP) !== false) {
                    $ip = $part;
                } elseif (substr($part, 0, 1) == '/') {
                    $path = $part;
                } elseif (in_array($part, ['GET', 'POST', 'PUT', 'DELETE'])) {
                    $method = $part;
                } elseif (preg_match('/^([A-z\-]+): (.*)$/', $part)) {
                    $header = $part;
                } else {
                    $body = $part;
                }
            }
        }

        $filter = new self();
        $filter->from      = $from;
        $filter->to        = $to;
        $filter->appId     = $appId;
        $filter->routeId   = $routeId;
        $filter->ip        = $ip;
        $filter->userAgent = $userAgent;
        $filter->method    = $method;
        $filter->path      = $path;
        $filter->header    = $header;
        $filter->body      = $body;

        return $filter;
    }
}
