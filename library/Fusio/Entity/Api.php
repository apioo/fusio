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
	 * @ManyToOne(targetEntity="Fusio\Entity\Model")
	 */
	protected $model;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\View")
	 */
	protected $view;

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

	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getStatus()
	{
		return $this->status;
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

	public function setModel($model)
	{
		$this->model = $model;
	}
	
	public function getModel()
	{
		return $this->model;
	}

	public function setView($view)
	{
		$this->view = $view;
	}
	
	public function getView()
	{
		return $this->view;
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
