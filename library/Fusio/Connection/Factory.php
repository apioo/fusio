<?php

namespace Fusio\Connection;

use InvalidArgumentException;

class Factory
{
	public static function factory($type)
	{
		$className = 'Fusio\\Connection\\' . ucfirst($type) . '\\Factory';
		if (class_exists($className)) {
			$factory = new $className();
			if ($factory instanceof FactoryInterface) {
				return $factory;
			} else {
				throw new InvalidArgumentException('Factory is not an instance of FactoryInterface');
			}
		} else {
			throw new InvalidArgumentException('Invalid connection type');
		}
	}
}
