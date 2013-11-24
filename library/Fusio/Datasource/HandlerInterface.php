<?php

namespace Fusio\Datasource;

use PSX\Data\RecordInterface;
use PSX\Sql\Condition;

abstract class HandlerInterface
{
	/**
	 * Connects to the given datasource. The params contains all connection 
	 * fields
	 *
	 * @param array $params
	 */
	abstract public function connect(array $params);

	/**
	 * Disconnect form the datasource
	 */
	abstract public function disconnect();

	/**
	 * Returns an array of record from the datasource
	 *
	 * @param array $fields
	 * @param integer $startIndex
	 * @param integer $count
	 * @param string $sortBy
	 * @param integer $sortOrder
	 * @param PSX\Sql\Condition $con
	 * @return PSX\Data\ResultSet
	 */
	abstract public function getAll(array $fields = null, $startIndex = 0, $count = 16, $sortBy = null, $sortOrder = null, Condition $con = null);

	/**
	 * Inserts an record into the datasource
	 *
	 * @param PSX\Data\RecordInterface $record
	 */
	abstract public function insert(RecordInterface $record);

	/**
	 * Updates an record from the datasource
	 *
	 * @param PSX\Data\RecordInterface $record
	 */
	abstract public function update(RecordInterface $record);

	/**
	 * Deletes an record from the datasource
	 *
	 * @param PSX\Data\RecordInterface $record
	 */
	abstract public function delete(RecordInterface $record);

	/**
	 * Returns an array wich fields wich are needed for connection
	 *
	 * @return array
	 */
	abstract public function getConnectionFields();
}