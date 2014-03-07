<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_app_token")
 */
class AppToken
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
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $status;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $ip;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $token;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $expire;

	/**
	 * @Column(type="datetime")
	 * @var DateTime
	 */
	protected $date;
}
