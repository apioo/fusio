<?php

namespace Fusio\Managment\Application;

use PSX\Controller\HandlerApiAbstract;

class App extends HandlerApiAbstract
{
	protected function getDefaultHandler()
	{
		return $this->getDoctrineManager()
			->getHandler(function($manager){
				return $manager->createQueryBuilder()
					->from('Fusio\Entity\App', 'app');
			});
	}
}
