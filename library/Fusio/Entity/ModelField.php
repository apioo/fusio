<?php

namespace Fusio\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="fusio_model_field")
 */
class ModelField
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Model", inversedBy="fields")
	 */
	protected $model;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Model")
	 */
	protected $reference;

	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $sortId;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $type;

	/**
	 * @Column(type="integer")
	 * @var string
	 */
	protected $length;

	/**
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $required;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $name;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $description;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ModelFilter", mappedBy="field")
	 */
	protected $filter;

	public function __construct()
	{
		$this->filter = new ArrayCollection();
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setModel($model)
	{
		$this->model = $model;
	}
	
	public function getModel()
	{
		return $this->model;
	}

	public function setSortId($sortId)
	{
		$this->sortId = $sortId;
	}
	
	public function getSortId()
	{
		return $this->sortId;
	}

	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getType()
	{
		return $this->type;
	}

	public function setRequired($required)
	{
		$this->required = $required;
	}
	
	public function getRequired()
	{
		return $this->required;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description;
	}

	public function getFilter()
	{
		return $this->filter;
	}
}
