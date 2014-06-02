<?php

namespace Fusio\Backend\Application;

use PSX\Controller\HandlerApiAbstract;

class Parser extends HandlerApiAbstract
{
	protected function getDefaultHandler()
	{
		return $this->getDoctrineManager()
			->getHandler(function($manager){
				return $manager->createQueryBuilder()
					->from('Fusio\Entity\Parser', 'parser');
			});
	}
}
