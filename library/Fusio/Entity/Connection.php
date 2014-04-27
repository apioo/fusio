<?php

namespace Fusio\Entity;

use Fusio\Connection\Factory;

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
		return json_decode($this->param, true);
	}

	public function getConnector()
	{
		return Factory::factory($this->getType())->getConnector($this->getParam());
	}
}
