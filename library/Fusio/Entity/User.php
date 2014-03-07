<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_user")
 */
class User
{
	/**
	 * @Id 
	 * @GeneratedValue 
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $id;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $name;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $password;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $email;

	/**
	 * @Column(type="datetime")
	 * @var DateTime
	 */
	protected $date;
}
