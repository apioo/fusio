<?php

namespace Fusio;

use PSX\Sql;

class Api
{
	const GET    = 'GET';
	const POST   = 'POST';
	const PUT    = 'PUT';
	const DELETE = 'DELETE';

	protected $id;
	protected $datasourceId;
	protected $allowedMethods;
	protected $path;
	protected $description;

	protected $_sql;
	protected $_fields;

	public function __construct(Sql $sql)
	{
		$this->_sql = $sql;
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setDatasourceId($datasourceId)
	{
		$this->datasourceId = $datasourceId;
	}
	
	public function getDatasourceId()
	{
		return $this->datasourceId;
	}

	public function setAllowedMethods($allowedMethods)
	{
		$this->allowedMethods = $allowedMethods;
	}
	
	public function getAllowedMethods()
	{
		return explode(',', $this->allowedMethods);
	}

	public function setPath($path)
	{
		$this->path = $path;
	}
	
	public function getPath()
	{
		return $this->path;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Returns the datasource for this api
	 *
	 * @return Fusio\Datasource
	 */
	public function getDatasource()
	{
		
	}

	/**
	 * Returns whether the method is allowed on this api
	 *
	 * @param string $method
	 * @return boolean
	 */
	public function isMethodAllowed($method)
	{
		return in_array($method, $this->getAllowedMethods());
	}

	/**
	 * Returns whether the request limit has exceeded for this app and method
	 *
	 * @return boolean
	 */
	public function hasRequestLimitExceeded(App $app, $method)
	{
		return false;
	}

	/**
	 * Returns all fields related to this api
	 *
	 * @return array<Fusio\Api\Field>
	 */
	public function getFields()
	{
		if ($this->_fields == null) {
			$sql = 'SELECT 
						`field`.`id`,
						`field`.`apiId`,
						`field`.`objectId`,
						`field`.`method`,
						`field`.`sort`,
						`field`.`name`,
						`field`.`description`,
						`field`.`required`,
						IF(`object`.`name` IS NULL, "String", `object`.`name`) AS `objectName`
					FROM 
						`fusio_api_field` `field`
					LEFT JOIN
						`fusio_object` `object`
						ON (`field`.`objectId` = `object`.`id`)
					WHERE
						`field`.`apiId` = ? 
					ORDER BY 
						`field`.`sort` ASC';

			$this->_fields = $this->_sql->getAll($sql, array($this->id), Sql::FETCH_OBJECT, '\Fusio\Api\Field');
		}

		return $this->_fields;
	}

	/**
	 * Returns all fields depending on the request method
	 *
	 * @param string $method
	 * @return array<Fusio\Api\Field>
	 */
	public function getFieldsByType($method)
	{
		$result = array();
		foreach ($this->getFields() as $field) {
			if ($field->getMethod() == $method) {
				$result[] = $field;
			}
		}
		return $result;
	}
}
