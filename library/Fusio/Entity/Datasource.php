<?php

namespace Fusio\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="fusio_datasource")
 */
class Datasource
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Connection")
	 */
	protected $connection;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $param;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\DatasourceField", mappedBy="datasource")
	 */
	protected $fields;

	public function __construct()
	{
		$this->fields = new ArrayCollection();
	}
}
