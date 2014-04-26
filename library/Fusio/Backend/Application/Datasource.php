<?php

namespace Fusio\Backend\Application;

use Fusio\Controller\BackendController;

class Datasource extends BackendController
{
	public function getParameters()
	{
		$connectionId = $this->getUriFragments('connection_id');
		$connection   = $this->getEntityManager()
			->getRepository('Fusio\Entity\Connection')
			->find($connectionId);

		if($connection instanceof Connection)
		{
			$class = 'Fusio\\Connection\\' . ucfirst($connection->getType());

			if(class_exists($class))
			{
				return $this->setBody(array(
					'parameters' => call_user_func($class . '::getDatasourceParameters'),
				));
			}
			else
			{
				throw new \Exception('Type does not exist');
			}
		}
		else
		{
			throw new \Exception('Invalid connection');
		}
	}
}
