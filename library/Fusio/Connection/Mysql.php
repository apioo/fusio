<?php

namespace Fusio\Connection;

use Fusio\ConnectionInterface;
use InvalidArgumentException;
use PSX\Sql;

class Mysql implements ConnectionInterface
{
	public function connect(array $params)
	{
		$host = isset($params['host']) ? $params['host'] : null;
		$user = isset($params['user']) ? $params['user'] : null;
		$pw   = isset($params['pw'])   ? $params['pw']   : null;
		$db   = isset($params['db'])   ? $params['db']   : null;

		if(empty($host))
		{
			throw new InvalidArgumentException('Host not set');
		}

		if(empty($user))
		{
			throw new InvalidArgumentException('User not set');
		}

		if(empty($db))
		{
			throw new InvalidArgumentException('Database not set');
		}

		return new Sql($host, $user, $pw, $db);
	}

	public static function getConnectionParameters()
	{
		return array('host', 'user', 'pw', 'db');
	}

	public static function getDatasourceParameters()
	{
		return array('table');
	}
}
