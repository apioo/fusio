<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_api_trigger")
 */
class ApiTrigger
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Api", inversedBy="trigger")
	 */
	protected $api;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Trigger")
	 */
	protected $trigger;
	
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $method;
}
