<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_app")
 */
class App
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $status;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $title;

	/**
	 * @Column(type="text")
	 * @var string
	 */
	protected $description;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $clientId;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $clientSecret;

	/**
	 * @Column(type="datetime")
	 * @var DateTime
	 */
	protected $date;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\AppRight", mappedBy="app")
	 */
	protected $rights;
}
