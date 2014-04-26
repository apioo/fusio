<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_api_limit")
 */
class ApiLimit
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Api", inversedBy="limits")
	 */
	protected $api;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $method;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $interval;

	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $count;

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setApi($api)
	{
		$this->api = $api;
	}
	
	public function getApi()
	{
		return $this->api;
	}

	public function setMethod($method)
	{
		$this->method = $method;
	}
	
	public function getMethod()
	{
		return $this->method;
	}

	public function setInterval($interval)
	{
		$this->interval = $interval;
	}
	
	public function getInterval()
	{
		return $this->interval;
	}

	public function setCount($count)
	{
		$this->count = $count;
	}
	
	public function getCount()
	{
		return $this->count;
	}
}
