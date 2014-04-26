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
	 * @ManyToOne(targetEntity="Fusio\Entity\User", inversedBy="apps")
	 */
	protected $user;

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

	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function getTitle()
	{
		return $this->title;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description;
	}

	public function setClientId($clientId)
	{
		$this->clientId = $clientId;
	}
	
	public function getClientId()
	{
		return $this->clientId;
	}

	public function setClientSecret($clientSecret)
	{
		$this->clientSecret = $clientSecret;
	}
	
	public function getClientSecret()
	{
		return $this->clientSecret;
	}

	public function setDate($date)
	{
		$this->date = $date;
	}
	
	public function getDate()
	{
		return $this->date;
	}
}
