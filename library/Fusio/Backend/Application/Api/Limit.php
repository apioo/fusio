<?php

namespace Fusio\Backend\Application\Api;

use PSX\Controller\HandlerApiAbstract;

class Limit extends HandlerApiAbstract
{
	protected function getDefaultHandler()
	{
		return $this->getDoctrineManager()
			->getHandler(function($manager){
				return $manager->createQueryBuilder()
					->from('Fusio\Entity\ApiLimit', 'limit');
			});
	}
}
