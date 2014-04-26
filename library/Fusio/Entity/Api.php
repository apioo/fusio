<?php

namespace Fusio\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="fusio_api")
 */
class Api
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $status;

	/**
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $default;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $allowedMethod;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $path;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $description;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ApiAction", mappedBy="api")
	 */
	protected $actions;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ApiLimit", mappedBy="api")
	 */
	protected $limits;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ApiTrigger", mappedBy="api")
	 */
	protected $trigger;

	public function __construct()
	{
		$this->limits  = new ArrayCollection();
		$this->trigger = new ArrayCollection();
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setDatasource($datasource)
	{
		$this->datasource = $datasource;
	}
	
	public function getDatasource()
	{
		return $this->datasource;
	}

	public function setModel($model)
	{
		$this->model = $model;
	}
	
	public function getModel()
	{
		return $this->model;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getStatus()
	{
		return $this->status;
	}

	public function setDefault($default)
	{
		$this->default = $default;
	}
	
	public function getDefault()
	{
		return $this->default;
	}

	public function setAllowedMethod($allowedMethod)
	{
		$this->allowedMethod = $allowedMethod;
	}
	
	public function getAllowedMethod()
	{
		return $this->allowedMethod;
	}

	public function isMethodAllowed($method)
	{
		return strpos($method, $this->allowedMethod) !== false;
	}

	public function setPath($path)
	{
		$this->path = $path;
	}
	
	public function getPath()
	{
		return $this->path;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description;
	}

	public function getActions()
	{
		return $this->actions;
	}

	public function getLimits()
	{
		return $this->limits;
	}

	public function getTrigger()
	{
		return $this->trigger;
	}
}
