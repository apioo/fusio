<?php

namespace Fusio\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="fusio_model")
 */
class Model
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @Column(type="string", length=64)
	 * @var string
	 */
	protected $name;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ModelField", mappedBy="model")
	 */
	protected $fields;

	public function __construct()
	{
		$this->fields = new ArrayCollection();
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}
}
