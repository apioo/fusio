<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_connection")
 */
class Connection
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $type;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $name;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $param;

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getType()
	{
		return $this->type;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setParam($param)
	{
		$this->param = $param;
	}
	
	public function getParam()
	{
		return $this->param;
	}

	public function getConnection()
	{
		$className = $this->type;

		if(class_exists($className))
		{
			$param = array();
			parse_str($this->param, $param);

			$connection = new $className();

			return $connection->connect($param);
		}
	}
}
