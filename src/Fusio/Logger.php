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

namespace Fusio;

use Doctrine\DBAL\Connection;
use PSX\DisplayException;
use PSX\Http\RequestInterface;
use PSX\Http\Stream\Util;

/**
 * Logger
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Logger
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function log($appId, $routeId, $ip, RequestInterface $request)
    {
        $now = new \DateTime();

        $this->connection->insert('fusio_log', array(
            'appId'     => $appId,
            'routeId'   => $routeId,
            'ip'        => $ip,
            'userAgent' => $request->getHeader('User-Agent'),
            'method'    => $request->getMethod(),
            'path'      => $request->getRequestTarget(),
            'header'    => $this->getHeadersAsString($request),
            'body'      => $this->getBodyAsString($request),
            'date'      => $now->format('Y-m-d H:i:s'),
        ));

        return $this->connection->lastInsertId();
    }

    public function appendError($logId, \Exception $exception)
    {
        if ($exception instanceof DisplayException) {
            return;
        }

        $previousException = $exception->getPrevious();
        if ($previousException instanceof \Exception) {
            $this->appendError($logId, $previousException);
        }

        $this->connection->insert('fusio_log_error', array(
            'logId'   => $logId,
            'message' => $exception->getMessage(),
            'trace'   => $exception->getTraceAsString(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
        ));
    }

    protected function getHeadersAsString(RequestInterface $request)
    {
        $headers = $request->getHeaders();
        $result  = '';

        foreach ($headers as $name => $value) {
            $name = strtr($name, '-', ' ');
            $name = strtr(ucwords(strtolower($name)), ' ', '-');

            $result.= $name . ': ' . implode(', ', $value) . "\n";
        }

        return rtrim($result);
    }

    protected function getBodyAsString(RequestInterface $request)
    {
        $body = Util::toString($request->getBody());
        if (empty($body)) {
            $body = null;
        }

        return $body;
    }
}
