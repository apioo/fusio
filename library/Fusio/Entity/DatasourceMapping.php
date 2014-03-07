<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_datasource_mapping")
 */
class DatasourceMapping
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;
}
