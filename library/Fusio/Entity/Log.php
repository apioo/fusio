<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_log")
 */
class Log
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
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $level;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $message;

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

	public function setLevel($level)
	{
		$this->level = $level;
	}
	
	public function getLevel()
	{
		return $this->level;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}
	
	public function getMessage()
	{
		return $this->message;
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
