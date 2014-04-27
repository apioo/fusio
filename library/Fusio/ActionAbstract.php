<?php

namespace Fusio;

use PSX\Http\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

abstract class ActionAbstract implements ActionInterface, ContainerAwareInterface
{
	protected $container;

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
}
