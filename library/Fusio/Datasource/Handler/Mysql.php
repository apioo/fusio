<?php

namespace Fusio\Datasource\Handler;

use Fusio\Datasource\HandlerInterface;
use PSX\Sql;

class Mysql extends HandlerInterface
{
	protected $sql;

	public function connect(array $params)
	{
		$this->sql = new Sql($params['host'], $params['user'], $params['pw'], $params['db']);
	}

	public function disconnect()
	{
		unset($this->sql);
	}

	public function getAll(array $fields = null, $startIndex = 0, $count = 16, $sortBy = null, $sortOrder = null, Condition $con = null)
	{
	}

	public function insert(RecordInterface $record)
	{
	}

	public function update(RecordInterface $record)
	{
	}

	public function delete(RecordInterface $record)
	{
	}

	public function getConnectionFields()
	{
		return array(
			'host',
			'user',
			'pw',
			'db',
			'table',
		);
	}
}
