<?php

namespace Fusio\Backend\Application\Connection;

use Fusio\Connection\Factory;
use Fusio\Connection\FactoryInterface;
use Fusio\Controller\BackendController;

class Types extends BackendController
{
	public function getTypes()
	{
		$types = array('types' => array('mysql'));

		return $this->setBody($types);
	}

	public function getParameters()
	{
		$type = $this->getUriFragments('type');

		return $this->setBody(array(
			'parameters' => Factory::factory($type)->getParameters(),
		));
	}
}
