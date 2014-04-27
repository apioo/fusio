<?php

namespace Fusio\Connection\Mysql;

use Fusio\Connection\FactoryInterface;
use Fusio\Parameter;
use InvalidArgumentException;
use PDO;

class Factory implements FactoryInterface
{
	public function getConnector(array $params)
	{
		$host = isset($params['host']) ? $params['host'] : null;
		$user = isset($params['user']) ? $params['user'] : null;
		$pw   = isset($params['pw'])   ? $params['pw']   : null;
		$db   = isset($params['db'])   ? $params['db']   : null;

		if (empty($host)) {
			throw new InvalidArgumentException('Host not set');
		}

		if (empty($user)) {
			throw new InvalidArgumentException('User not set');
		}

		if (empty($db)) {
			throw new InvalidArgumentException('Database not set');
		}

		$pdo = new Connector(sprintf('mysql:host=%s;dbname=%s;charset=UTF8', $host, $db), $user, $pw, array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		));

		return $pdo;
	}

	public function getParameters()
	{
		return array(
			new Parameter\Text('host', 'Host'),
			new Parameter\Text('user', 'User'),
			new Parameter\Text('pw', 'Password'),
			new Parameter\Text('db', 'Database'),
		);
	}
}
