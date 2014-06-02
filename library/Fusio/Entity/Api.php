<?php

namespace Fusio\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="fusio_api")
 */
class Api
{
	const STATUS_DEVELOPMENT = 1;
	const STATUS_LIVE        = 2;
	const STATUS_DEPRECATED  = 3;
	const STATUS_REMOVED     = 4;

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
	protected $path;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $description;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ApiMethod", mappedBy="api")
	 */
	protected $methods;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ApiLimit", mappedBy="api")
	 */
	protected $limits;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ApiTrigger", mappedBy="api")
	 */
	protected $trigger;

	public function __construct()
	{
		$this->methods = new ArrayCollection();
		$this->limits  = new ArrayCollection();
		$this->trigger = new ArrayCollection();
	}

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

	public function setPath($path)
	{
		$this->path = $path;
	}
	
	public function getPath()
	{
		return $this->path;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description;
	}

	public function geMethods()
	{
		return $this->methods;
	}

	public function getLimits()
	{
		return $this->limits;
	}

	public function getTrigger()
	{
		return $this->trigger;
	}

	public function getAllowedMethods()
	{
		$methods = array();
		foreach($this->methods as $method)
		{
			$methods[] = $method->getMethod();
		}

		return $methods;
	}
}
