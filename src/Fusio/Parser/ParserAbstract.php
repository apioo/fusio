<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Parser;

use DirectoryIterator;
use Fusio\ConfigurableInterface;
use Fusio\Factory\FactoryInterface;
use ReflectionClass;

/**
 * ParserAbstract
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
abstract class ParserAbstract
{
	protected $factory;
	protected $paths;
	protected $instanceOf;

	public function __construct(FactoryInterface $factory, array $paths, $instanceOf)
	{
		$this->factory    = $factory;
		$this->paths      = $paths;
		$this->instanceOf = $instanceOf;
	}

	public function getClasses()
	{
		$result = array();

		foreach($this->paths as $path)
		{
			if(is_dir($path))
			{
				$dir = new DirectoryIterator($path);

				foreach($dir as $file)
				{
					if($file->isFile() && $file->getExtension() == 'php')
					{
						$classes = $this->getDefinedClasses($file->getPathname());

						foreach($classes as $class)
						{
							$object     = $this->getClass($class);
							$instanceOf = $this->instanceOf;

							if($object instanceof $instanceOf)
							{
								$result[] = array(
									'name'  => $object->getName(),
									'class' => $class,
								);
							}
						}
					}
				}
			}
		}

		return $result;
	}

	public function getForm($className)
	{
		$object = $this->getClass($className);

		if($object instanceof ConfigurableInterface)
		{
			return $object->getForm();
		}

		return null;
	}

	public function getClass($className)
	{
		if(empty($className) || !is_string($className))
		{
			throw new \RuntimeException('Invalid class name');
		}

		return $this->factory->factory($className);
	}

	protected function getDefinedClasses($path)
	{
		$existingClasses = get_declared_classes();

		include_once $path;

		$newClasses = get_declared_classes();

		return array_diff($newClasses, $existingClasses);
	}
}
