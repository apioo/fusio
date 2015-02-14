<?php

namespace Fusio\Connection;

use Doctrine\DBAL\DriverManager;
use Fusio\ConnectionInterface;
use Fusio\Parameters;
use Fusio\Form;
use Fusio\Form\Element;

class Native implements ConnectionInterface
{
	public function getName()
	{
		return 'Native';
	}

	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection(Parameters $config)
	{
		return $this->connection;
	}

	public function getForm()
	{
		$form = new Form\Container();

		return $form;
	}
}
