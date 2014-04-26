<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_usage")
 */
class Usage
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Api")
	 */
	protected $api;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\App")
	 */
	protected $app;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $ip;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $method;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $path;

	/**
	 * @Column(type="datetime")
	 * @var DateTime
	 */
	protected $date;

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

	public function setApp($app)
	{
		$this->app = $app;
	}
	
	public function getApp()
	{
		return $this->app;
	}

	public function setIp($ip)
	{
		$this->ip = $ip;
	}
	
	public function getIp()
	{
		return $this->ip;
	}

	public function setMethod($method)
	{
		$this->method = $method;
	}
	
	public function getMethod()
	{
		return $this->method;
	}

	public function setPath($path)
	{
		$this->path = $path;
	}
	
	public function getPath()
	{
		return $this->path;
	}

	public function setDate($date)
	{
		$this->date = $date;
	}
	
	public function getDate()
	{
		return $this->date;
	}
}
