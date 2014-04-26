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
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $status;

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

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\App", mappedBy="user")
	 */
	protected $apps;

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getStatus()
	{
		return $this->status;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}
	
	public function getPassword()
	{
		return $this->password;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	public function getEmail()
	{
		return $this->email;
	}

	public function setDate($date)
	{
		$this->date = $date;
	}
	
	public function getDate()
	{
		return $this->date;
	}

	public function getApps()
	{
		return $this->apps;
	}
}
