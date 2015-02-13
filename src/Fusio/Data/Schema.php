<?php

namespace Fusio\Data;

use PSX\Data\SchemaInterface;
use PSX\Data\Schema\PropertyInterface;

class Schema implements SchemaInterface
{
	protected $property;

	public function __construct(PropertyInterface $property)
	{
		$this->property = $property;
	}

	public function getDefinition()
	{
		return $this->property;
	}
}
