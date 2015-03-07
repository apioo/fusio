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

namespace Fusio;

use Doctrine\DBAL\Connection;
use PSX\Http\Stream\Util;
use PSX\Http\RequestInterface;

/**
 * Logger
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
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
		$sql = 'INSERT INTO fusio_log 
				        SET `appId` = :appId,
				            `routeId` = :routeId,
				            `ip` = :ip,
				            `method` = :method,
				            `path` = :path,
				            `header` = :header,
				            `body` = :body,
				            `date` = NOW()';

		$this->connection->executeUpdate($sql, array(
			'appId'   => $appId,
			'routeId' => $routeId,
			'ip'      => $ip,
			'method'  => $request->getMethod(),
			'path'    => $request->getRequestTarget(),
			'header'  => $this->getHeadersAsString($request),
			'body'    => $this->getBodyAsString($request),
		));
	}

	protected function getHeadersAsString(RequestInterface $request)
	{
		$headers = $request->getHeaders();
		$result  = '';

		foreach($headers as $name => $value)
		{
			$name = strtr($name, '-', ' ');
			$name = strtr(ucwords(strtolower($name)), ' ', '-');

			$result.= $name . ': ' . implode(', ', $value) . "\n";
		}

		return rtrim($result);
	}

	protected function getBodyAsString(RequestInterface $request)
	{
		$body = Util::toString($request->getBody());
		if(empty($body))
		{
			$body = null;
		}

		return $body;
	}
}
