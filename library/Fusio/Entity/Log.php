<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_log")
 */
class Log
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\Api")
	 */
	protected $api;

	/**
	 * @ManyToOne(targetEntity="Fusio\Entity\App")
	 */
	protected $app;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $ip;

	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $level;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $message;

	/**
	 * @Column(type="datetime")
	 * @var DateTime
	 */
	protected $date;
}
