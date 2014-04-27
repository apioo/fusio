<?php

namespace Fusio\Connection\Mysql;

use Fusio\Connection\ConnectorInterface;
use Fusio\Connection\FactoryInterface;
use PDO;
use PSX\Data\RecordInterface;
use PSX\Sql\Condition;

class Connector extends \PDO implements ConnectorInterface
{
	public function getAll($table, array $fields, $startIndex = 0, $count = 16, $sortBy = null, $sortOrder = null, Condition $con = null)
	{
	}

	public function insert($table, RecordInterface $record)
	{
	}

	public function update($table, RecordInterface $record)
	{
	}

	public function delete($table, RecordInterface $record)
	{
	}

	public function getTables()
	{
		$statment = $this->query('SHOW TABLES');

		$tables = array();
		$result = $statment->fetchAll(PDO::FETCH_COLUMN);

		foreach ($result as $row) {
			$tables[] = $row;
		}

		return $tables;
	}

	public function getFields($table)
	{
		$statment = $this->prepare('DESCRIBE ' . $table);
		$statment->execute();

		$fields = array();
		$result = $statment->fetchAll(PDO::FETCH_COLUMN);

		foreach ($result as $row) {
			$fields[] = $row;
		}

		return $fields;
	}
}
