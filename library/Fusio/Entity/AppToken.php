<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_app_token")
 */
class AppToken
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\App", inversedBy="rights")
	 */
	protected $app;

	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $status;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $ip;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $token;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $expire;

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

	public function setApp($app)
	{
		$this->app = $app;
	}
	
	public function getApp()
	{
		return $this->app;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getStatus()
	{
		return $this->status;
	}

	public function setIp($ip)
	{
		$this->ip = $ip;
	}
	
	public function getIp()
	{
		return $this->ip;
	}

	public function setToken($token)
	{
		$this->token = $token;
	}
	
	public function getToken()
	{
		return $this->token;
	}

	public function setExpire($expire)
	{
		$this->expire = $expire;
	}
	
	public function getExpire()
	{
		return $this->expire;
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
