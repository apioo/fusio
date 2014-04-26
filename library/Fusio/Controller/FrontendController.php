<?php

namespace Fusio\Controller;

use Fusio\User;
use PSX\Controller\ViewAbstract;

class FrontendController extends ViewAbstract
{
	protected $user;

	public function onLoad()
	{
		$this->container->setParameter('session.name', 'fusio_frontend');

		$this->user = $this->getCurrentUser();

		$this->getTemplate()->assign('user', $this->user);
	}

	protected function getCurrentUser()
	{
		$user = new User();
		$user->setId(0);
		$user->setName('Anonymous');
		$user->setAuthenticated(false);

		$userId = $this->getSession()->get('user_id');
		if(!empty($userId))
		{
			$userEntity = $this->getEntityManager()
				->getRepository('Fusio\Entity\User')
				->find($userId);

			$user->setId($userEntity->getId());
			$user->setName($userEntity->getName());
			$user->setAuthenticated(true);
		}

		return $user;
	}
}
