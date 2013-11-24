<?php

namespace Fusio;

class Datasource extends RecordAbstract
{
	protected $_handler;

	public function __destruct()
	{
		$this->getHandler()->disconnect();
	}

	public function getAll(array $fields = null, $startIndex = 0, $count = 16, $sortBy = null, $sortOrder = null, Condition $con = null)
	{
		return $this->getHandler()->getAll($fields, $startIndex, $count, $sortBy, $sortOrder, $con);
	}

	public function insert(RecordInterface $record)
	{
		return $this->getHandler()->insert($record);
	}

	public function update(RecordInterface $record)
	{
		return $this->getHandler()->update($record);
	}

	public function delete(RecordInterface $record)
	{
		return $this->getHandler()->delete($record);
	}

	protected function getHandler()
	{
		if ($this->_handler === null) {
			$class = '\Fusio\Datasource\Handler\\' . ucfirst(strtolower($this->type));

			if (class_exists($class)) {
				$this->_handler = new $class();
				$this->_handler->connect($this->params);
			} else {
				throw new RuntimeException('Unknown datasource type');
			}
		}

		return $this->_handler;
	}
}
