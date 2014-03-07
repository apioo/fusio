<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_usage")
 */
class Usage
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;
}
