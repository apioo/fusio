<?php

namespace Fusio;

use Doctrine\DBAL\Connection;
use PSX\Dependency\ObjectBuilderInterface;

class ActionFactory
{
	protected $objectBuilder;

	public function __construct(ObjectBuilderInterface $objectBuilder)
	{
		$this->objectBuilder = $objectBuilder;
	}

	public function getAction($className)
	{
		return $this->objectBuilder->getObject($className, array(), 'Fusio\ActionInterface');
	}
}
