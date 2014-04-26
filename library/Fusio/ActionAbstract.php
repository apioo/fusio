<?php

namespace Fusio;

use PSX\Http\Request;

abstract class ActionAbstract implements ActionInterface, ContainerAwareInterface
{
	protected $container;

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
}
