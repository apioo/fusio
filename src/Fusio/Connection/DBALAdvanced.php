<?php

namespace Fusio\Connection;

use Doctrine\DBAL\DriverManager;
use Fusio\ConnectionInterface;
use Fusio\Parameters;
use Fusio\Form;
use Fusio\Form\Element;

class DBALAdvanced implements ConnectionInterface
{
	public function getName()
	{
		return 'SQL-Connection (advanced)';
	}

	/**
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection(Parameters $config)
	{
		return DriverManager::getConnection(array(
			'url' => $config->get('url')
		));
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Input('url', 'Url'));

		return $form;
	}
}
