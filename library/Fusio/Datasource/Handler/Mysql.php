<?php

namespace Fusio\Connection\Handler;

use Fusio\DatasourceInterface;
use PSX\Handler\HandlerQueryAbstract;
use PSX\Sql;

class Mysql extends HandlerQueryAbstract
{
	protected $sql;
	protected $table;
	protected $query;

	protected $supportedFields;

	public function __construct(Sql $sql, $params, ArrayCollection $fields = null)
	{
		$this->sql    = $sql;
		$this->table  = $params['table'];
		$this->query  = $params['query'];


		$supportedFields = array();
		if($fields !== null)
		{
			foreach($this->fields as $field)
			{
				$supportedFields[] = $field->getName();
			}
		}
		$this->supportedFields = $supportedFields;
	}

	public function getAll(array $fields = array(), $startIndex = 0, $count = 16, $sortBy = null, $sortOrder = null, Condition $con = null)
	{
		$startIndex = $startIndex !== null ? (integer) $startIndex : 0;
		$count      = !empty($count)       ? (integer) $count      : 16;
		$sortBy     = $sortBy     !== null ? $sortBy               : $this->mapping->getIdProperty();
		$sortOrder  = $sortOrder  !== null ? (integer) $sortOrder  : Sql::SORT_DESC;

		$fields = array_intersect($fields, $this->getSupportedFields());

		if(empty($fields))
		{
			$fields = $this->getSupportedFields();
		}

		if(!in_array($sortBy, $this->getSupportedFields()))
		{
			$sortBy = $this->mapping->getIdProperty();
		}


		$sql = "SELECT {fields} FROM {table} INNER JOIN WHERE {condition} ORDER BY {order} LIMIT {limit}";

		$query->addOrderBy();


		$this->sql->assoc();

		$statment = $this->getSelectStatment($fields, $startIndex, $count, $sortBy, $sortOrder, $con);
		$statment->execute();

		$result = $statment->fetchAll(PDO::FETCH_ASSOC);
		$return = array();

		foreach($result as $entry)
		{
			$row = array();

			foreach($entry as $key => $value)
			{
				foreach($this->mapping->getFields() as $field => $type)
				{
					if($key == $field)
					{
						$row[$field] = $this->unserializeType($value, $type);
					}
				}
			}

			$return[] = new Record('record', $row);
		}

		return $return;
	}

	public function get($id, array $fields = array())
	{
		$con = new Condition(array($this->mapping->getIdProperty(), '=', $id));

		return $this->getOneBy($con, $fields);
	}

	public function getSupportedFields()
	{
		return $this->supportedFields;
	}

	public function getCount(Condition $con = null)
	{
		$statment = $this->getCountStatment($con);
		$statment->execute();

		$result = $statment->fetch(PDO::FETCH_NUM);

		if(isset($result[0]))
		{
			return (integer) $result[0];
		}

		return 0;
	}
}
