<?php

namespace Fusio\Connection;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Fusio\ConnectionInterface;
use Fusio\Parameters;
use Fusio\Form;
use Fusio\Form\Element;

class DBAL extends ConnectionInterface
{
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
