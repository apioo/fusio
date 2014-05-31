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
	 * @Column(type="smallint")
	 * @var string
	 */
	protected $methodGet;

	/**
	 * @Column(type="smallint")
	 * @var string
	 */
	protected $methodPost;

	/**
	 * @Column(type="smallint")
	 * @var string
	 */
	protected $methodPut;

	/**
	 * @Column(type="smallint")
	 * @var string
	 */
	protected $methodDelete;

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

	public function setMethodGet($methodGet)
	{
		$this->methodGet = (integer) $methodGet;
	}

	public function hasMethodGet()
	{
		return $this->methodGet == 1;
	}

	public function setMethodPost($methodPost)
	{
		$this->methodPost = (integer) $methodPost;
	}

	public function hasMethodPost()
	{
		return $this->methodPost == 1;
	}

	public function setMethodPut($methodPut)
	{
		$this->methodPut = (integer) $methodPut;
	}

	public function hasMethodPut()
	{
		return $this->methodPut == 1;
	}

	public function setMethodDelete($methodDelete)
	{
		$this->methodDelete = (integer) $methodDelete;
	}

	public function hasMethodDelete()
	{
		return $this->methodDelete == 1;
	}

	public function getAllowedMethods()
	{
		return array_filter(array(
			$this->hasMethodGet()    ? 'GET'    : null,
			$this->hasMethodPost()   ? 'POST'   : null,
			$this->hasMethodPut()    ? 'PUT'    : null,
			$this->hasMethodDelete() ? 'DELETE' : null,
		));
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
