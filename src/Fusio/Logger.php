<?php

namespace Fusio;

use Doctrine\DBAL\Connection;
use PSX\Http\Stream\Util;
use PSX\Http\RequestInterface;

class Logger
{
	protected $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	public function log($appId, $routeId, $ip, RequestInterface $request)
	{
		$body = Util::toString($request->getBody());
		if(empty($body))
		{
			$body = null;
		}

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
    		'header'  => serialize($request->getHeaders()),
    		'body'    => $body,
		));
	}
}
