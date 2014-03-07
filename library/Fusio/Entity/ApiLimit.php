<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_api_limit")
 */
class ApiLimit
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Api", inversedBy="limits")
	 */
	protected $api;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $method;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $interval;

	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $count;
}
