<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_datasource_field")
 */
class DatasourceField
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Datasource", inversedBy="fields")
	 */
	protected $datasource;

	/**
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $available;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $name;
}
