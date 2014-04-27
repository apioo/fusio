<?php

namespace Fusio\Connection;

use PSX\Data\RecordInterface;
use PSX\Sql\Condition;

interface ConnectorInterface
{
	public function getAll($table, array $fields, $startIndex = 0, $count = 16, $sortBy = null, $sortOrder = null, Condition $con = null);

	public function insert($table, RecordInterface $record);

	public function update($table, RecordInterface $record);

	public function delete($table, RecordInterface $record);

	/**
	 * Returns all available tables on this connection
	 *
	 * @param mixed $connection
	 * @return array
	 */
	public function getTables();

	/**
	 * Returns all available fields on this connection from an table
	 *
	 * @param mixed $connection
	 * @param string $table
	 * @return array
	 */
	public function getFields($table);
}
