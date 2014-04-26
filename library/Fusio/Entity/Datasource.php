<?php

namespace Fusio\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="fusio_datasource")
 */
class Datasource
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Connection")
	 */
	protected $connection;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $param;

	public function __construct()
	{
		$this->fields = new ArrayCollection();
	}

	public function setConnection($connection)
	{
		$this->connection = $connection;
	}
	
	public function getConnection()
	{
		return $this->connection;
	}

	public function setParam($param)
	{
		$this->param = $param;
	}
	
	public function getParam()
	{
		return $this->param;
	}

	public function getHandler()
	{
		$connection = $this->connection->getConnection();

		if($connection instanceof Connection\Mysql)
		{
			$handler = new Mysql($connection, $this->fields);
		}
		else
		{
			throw new \Exception('Could not establish a connection');
		}
	}
}
