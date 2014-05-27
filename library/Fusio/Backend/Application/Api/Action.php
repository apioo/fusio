<?php

namespace Fusio\Backend\Application\Api;

use PSX\Controller\HandlerApiAbstract;

class Action extends HandlerApiAbstract
{
	protected function getDefaultHandler()
	{
		return $this->getDoctrineManager()
			->getHandler(function($manager){
				return $manager->createQueryBuilder()
					->from('Fusio\Entity\ApiAction', 'action');
			});
	}
}
