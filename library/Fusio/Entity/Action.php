<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_action")
 */
class Action
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

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
	 * @Column(type="string")
	 * @var string
	 */
	protected $class;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ApiAction", mappedBy="action")
	 */
	protected $actions;

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

	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description;
	}

	public function setClass($class)
	{
		$this->class = $class;
	}
	
	public function getClass()
	{
		return $this->class;
	}

	public function getActions()
	{
		return $this->actions;
	}
}
