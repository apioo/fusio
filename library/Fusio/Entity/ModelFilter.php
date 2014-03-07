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
}
