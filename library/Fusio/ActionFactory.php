<?php

namespace Fusio;

use PSX\Http\Request;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ActionFactory
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function getAction($className)
	{
		if (class_exists($className)) {
			$action = new $className();

			if (!$action instanceof ActionInterface) {
				throw new InvalidArgumentException('Action must be an instance of ActionInterface');
			}

			if ($action instanceof ContainerAwareInterface) {
				$action->setContainer($this->container);
			}

			return $action;
		} else {
			throw new InvalidArgumentException('Class ' . $className . ' does not exist');
		}
	}
}
