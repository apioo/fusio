<?php

namespace Fusio;

use DirectoryIterator;
use ReflectionClass;

class ActionParser
{
	protected $factory;
	protected $paths;

	public function __construct(ActionFactory $factory, array $paths)
	{
		$this->factory = $factory;
		$this->paths   = $paths;
	}

	public function getActions()
	{
		$controllers = array();

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

						foreach($classes as $actionClass)
						{
							$action = $this->getAction($actionClass);

							if($action instanceof ActionInterface)
							{
								$controllers[] = array(
									'name'  => $action->getName(),
									'class' => $actionClass,
								);
							}
						}
					}
				}
			}
		}

		return $controllers;
	}

	public function getForm($className)
	{
		$action = $this->getAction($className);

		if($action instanceof ActionInterface)
		{
			return $action->getForm();
		}

		return null;
	}

	public function getAction($className)
	{
		if(empty($className) || !is_string($className))
		{
			throw new \RuntimeException('Invalid class name');
		}

		return $this->factory->getAction($className);;
	}

	protected function getDefinedClasses($path)
	{
		$existingClasses = get_declared_classes();

		include_once $path;

		$newClasses = get_declared_classes();

		return array_diff($newClasses, $existingClasses);
	}
}
