<?php

namespace Fusio;

use ArrayIterator;
use IteratorAggregate;

class Parameters implements IteratorAggregate
{
	protected $container;

	public function __construct(array $container)
	{
		$this->container = $container;
	}

	public function get($key)
	{
		return isset($this->container[$key]) ? $this->container[$key] : null;
	}

	public function has($key)
	{
		return isset($this->container[$key]);
	}

	public function isEmpty()
	{
		return empty($this->container);
	}

	public function getIterator()
	{
		return new ArrayIterator($this->container);
	}
}
