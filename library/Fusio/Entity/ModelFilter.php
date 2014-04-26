<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_model_filter")
 */
class ModelFilter
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\ModelField", inversedBy="filter")
	 */
	protected $field;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $className;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $param;

	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $priority;

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setField($field)
	{
		$this->field = $field;
	}
	
	public function getField()
	{
		return $this->field;
	}

	public function setClassName($className)
	{
		$this->className = $className;
	}
	
	public function getClassName()
	{
		return $this->className;
	}

	public function setParam($param)
	{
		$this->param = $param;
	}
	
	public function getParam()
	{
		return $this->param;
	}

	public function setPriority($priority)
	{
		$this->priority = $priority;
	}
	
	public function getPriority()
	{
		return $this->priority;
	}
}
