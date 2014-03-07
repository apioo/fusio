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
}
