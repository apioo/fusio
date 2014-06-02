<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_api_method")
 */
class ApiMethod
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Api", inversedBy="methods")
	 */
	protected $api;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $method;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Parser")
	 */
	protected $parser;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\View")
	 */
	protected $view;

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

	public function setParser($parser)
	{
		$this->parser = $parser;
	}

	public function getParser()
	{
		return $this->parser;
	}

	public function setView($view)
	{
		$this->view = $view;
	}
	
	public function getView()
	{
		return $this->view;
	}
}
