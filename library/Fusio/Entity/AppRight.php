<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_app_right")
 */
class AppRight
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\App", inversedBy="rights")
	 */
	protected $app;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Api")
	 */
	protected $api;

	/**
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $allowed;
}
