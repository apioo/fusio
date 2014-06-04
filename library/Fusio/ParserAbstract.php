<?php

namespace Fusio;

use Fusio\Context;
use Fusio\Entity\Model;
use PSX\Data\RecordInterface;
use PSX\Http\Request;
use PSX\Http\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ParserAbstract
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function getParameters()
	{
		return array();
	}

	abstract public function transform(Request $request, array $parameters, Model $model, Context $context);
}
