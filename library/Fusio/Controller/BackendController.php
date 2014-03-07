<?php

namespace Fusio\Controller;

use Fusio\User;
use PSX\Controller\ViewAbstract;

class BackendController extends ViewAbstract
{
	protected $user;

	public function onLoad()
	{
		$this->container->setParameter('session.name', 'fusio_backend');

		$this->user = $this->getCurrentUser();

		$this->getTemplate()->assign('user', $this->user);
	}

	protected function getCurrentUser()
	{
		$user = new User();
		$user->setAuthenticated(false);

		$userId = $this->getSession()->get('user_id');

		if(!empty($userId))
		{
			$record = $this->getDatabaseManager()
				->getHandler('Fusio\Frontend\User\Handler')
				->getById($userId);

			$user->setId($record->getId());
			$user->setName($record->getName());
			$user->setAuthenticated(true);
		}

		return $user;
	}
}
