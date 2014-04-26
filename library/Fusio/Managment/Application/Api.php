<?php

namespace Fusio\Managment\Application;

use PSX\Controller\HandlerApiAbstract;

class Api extends HandlerApiAbstract
{
	protected function getDefaultHandler()
	{
		return $this->getDoctrineManager()
			->getHandler(function($manager){
				return $manager->createQueryBuilder()
					->from('Fusio\Entity\Api', 'api');
			});
	}
}
