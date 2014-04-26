<?php

namespace Fusio\Controller;

use Fusio\User;
use PSX\Controller\ViewAbstract;
use PSX\Exception;

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
		$userId = $this->getSession()->get('user_id');
		if(!empty($userId))
		{
			$userEntity = $this->getEntityManager()
				->getRepository('Fusio\Entity\User')
				->find($userId);

			$user = new User();
			$user->setId($userEntity->getId());
			$user->setName($userEntity->getName());
			$user->setAuthenticated(true);
		}
		else
		{
			$user = new User();
			$user->setId(0);
			$user->setName('Anonymous');
			$user->setAuthenticated(false);
			//throw new Exception('Unauthorized request');
		}

		return $user;
	}
}
