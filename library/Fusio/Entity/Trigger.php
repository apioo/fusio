<?php

namespace Fusio\Entity;

/**
 * @Entity
 * @Table(name="fusio_trigger")
 */
class Trigger
{
	const TYPE_SQL_CREATE   = 'sql-create';
	const TYPE_SQL_UPDATE   = 'sql-update';
	const TYPE_SQL_DELETE   = 'sql-delete';
	const TYPE_PHP_TRIGGER  = 'php-trigger';
	const TYPE_CLI_EXECUTE  = 'cli-execute';
	const TYPE_HTTP_WEBHOOK = 'http-webhook';
	const TYPE_MQ_RABBITMQ  = 'mq-rabbitmq';

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
	protected $type;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $name;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $param;

	/**
	 * @OneToMany(targetEntity="Fusio\Entity\ApiTrigger", mappedBy="trigger")
	 */
	protected $actions;

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description;
	}

	public function setClass($class)
	{
		$this->class = $class;
	}
	
	public function getClass()
	{
		return $this->class;
	}

	public function getActions()
	{
		return $this->actions;
	}
}
