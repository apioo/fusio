<?php

namespace Fusio\Managment\Application\Api;

use PSX\Controller\HandlerApiAbstract;

class Trigger extends HandlerApiAbstract
{
	protected function getDefaultHandler()
	{
		return $this->getDoctrineManager()
			->getHandler(function($manager){
				return $manager->createQueryBuilder()
					->from('Fusio\Entity\ApiTrigger', 'trigger');
			});
	}
}
