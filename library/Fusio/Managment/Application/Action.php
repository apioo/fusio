<?php

namespace Fusio\Managment\Application;

use PSX\Controller\HandlerApiAbstract;

class Action extends HandlerApiAbstract
{
	protected function getDefaultHandler()
	{
		return $this->getDoctrineManager()
			->getHandler(function($manager){
				return $manager->createQueryBuilder()
					->from('Fusio\Entity\Action', 'action');
			});
	}
}
