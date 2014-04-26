<?php

namespace Fusio\Backend\Application;

use Fusio\Controller\BackendController;

class Connection extends BackendController
{
	public function getTypes()
	{
		$types = array('types' => array('mysql'));

		return $this->setBody($types);
	}

	public function getParameters()
	{
		$type  = $this->getUriFragments('type');
		$class = 'Fusio\\Connection\\' . ucfirst($type);

		if(class_exists($class))
		{
			return $this->setBody(array(
				'parameters' => call_user_func($class . '::getConnectionParameters'),
			));
		}
		else
		{
			throw new \Exception('Type does not exist');
		}
	}
}
