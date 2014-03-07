<?php

namespace Fusio\Api;

use Exception;

class Field
{
	protected $id;
	protected $apiId;
	protected $objectId;
	protected $method;
	protected $sort;
	protected $name;
	protected $description;
	protected $required;

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setApiId($apiId)
	{
		$this->apiId = $apiId;
	}
	
	public function getApiId()
	{
		return $this->apiId;
	}

	public function setObjectId($objectId)
	{
		$this->objectId = $objectId;
	}
	
	public function getObjectId()
	{
		return $this->objectId;
	}

	public function setMethod($method)
	{
		$this->method = $method;
	}
	
	public function getMethod()
	{
		return $this->method;
	}

	public function setSort($sort)
	{
		$this->sort = $sort;
	}
	
	public function getSort()
	{
		return $this->sort;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description;
	}

	public function setRequired($required)
	{
		$this->required = $required;
	}
	
	public function getRequired()
	{
		return $this->required;
	}

	public function isRequired()
	{
		return (boolean) $this->required;
	}
}
