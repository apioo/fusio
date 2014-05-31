<?php

namespace Fusio;

use PSX\Http\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class TriggerAbstract
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	abstract public function execute(Request $request, array $parameters, Context $Context = null);
}
