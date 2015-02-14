<?php

namespace Fusio\Factory;

use Fusio\Factory\FactoryInterface;
use PSX\Dependency\ObjectBuilderInterface;

class Connection implements FactoryInterface
{
	protected $objectBuilder;

	public function __construct(ObjectBuilderInterface $objectBuilder)
	{
		$this->objectBuilder = $objectBuilder;
	}

	public function factory($className)
	{
		return $this->objectBuilder->getObject($className, array(), 'Fusio\ConnectionInterface');
	}
}
