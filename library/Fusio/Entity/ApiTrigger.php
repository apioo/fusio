<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_api_trigger")
 */
class ApiTrigger
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Api", inversedBy="trigger")
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
	protected $param;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Trigger")
	 */
	protected $trigger;

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

	public function setTrigger($trigger)
	{
		$this->trigger = $trigger;
	}
	
	public function getTrigger()
	{
		return $this->trigger;
	}

	public function setMethod($method)
	{
		$this->method = $method;
	}
	
	public function getMethod()
	{
		return $this->method;
	}

	public function setParam($param)
	{
		$this->param = $param;
	}
	
	public function getParam()
	{
		return json_decode($this->param, true);
	}
}
