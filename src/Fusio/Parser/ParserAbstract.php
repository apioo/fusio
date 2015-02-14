<?php

namespace Fusio\Parser;

use DirectoryIterator;
use Fusio\ConfigurableInterface;
use Fusio\Factory\FactoryInterface;
use ReflectionClass;

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
