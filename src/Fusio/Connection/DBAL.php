<?php

namespace Fusio\Connection;

use Doctrine\DBAL\DriverManager;
use Fusio\ConnectionInterface;
use Fusio\Parameters;
use Fusio\Form;
use Fusio\Form\Element;

class DBAL implements ConnectionInterface
{
	public function getName()
	{
		return 'SQL-Connection';
	}

	/**
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection(Parameters $config)
	{
		$params = array(
			'dbname'   => $config->get('database'),
			'user'     => $config->get('username'),
			'password' => $config->get('password'),
			'host'     => $config->get('host'),
			'driver'   => $config->get('type'),
		);

		return DriverManager::getConnection($params);
	}

	public function getForm()
	{
		$types = array(
			'pdo_mysql'   => 'MySQL',
			'pdo_pgsql'   => 'PostgreSQL',
			'sqlsrv'      => 'Microsoft SQL Server',
			'oci8'        => 'Oracle Database ',
			'sqlanywhere' => 'SAP Sybase SQL Anywhere',
		);

		$form = new Form\Container();
		$form->add(new Element\Select('type', 'Type', $types));
		$form->add(new Element\Input('host', 'Host'));
		$form->add(new Element\Input('username', 'Username'));
		$form->add(new Element\Input('password', 'Password'));
		$form->add(new Element\Input('database', 'Database'));

		return $form;
	}
}
