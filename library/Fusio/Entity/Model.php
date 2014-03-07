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
	 * @Column(type="string")
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
}
