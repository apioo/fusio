<?php

namespace Fusio\Datasource\Handler;

use Fusio\Datasource\HandlerInterface;
use PSX\Http;

class Mysql extends HandlerInterface
{
	protected $http;

	public function connect(array $params)
	{
		$this->http = new Http();
	}

	public function disconnect()
	{
		unset($this->http);
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
			'url',
		);
	}
}
