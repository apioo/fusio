<?php

namespace Fusio\Trigger;

use Fusio\Parameter;
use Fusio\TriggerAbstract;
use PSX\Http\Request;

class PhpTrigger extends TriggerAbstract
{
	public function execute(Request $request, array $parameters)
	{
		$className = isset($parameters['class'])     ? $parameters['class']     : null;
		$arguments = isset($parameters['arguments']) ? $parameters['arguments'] : null;

		if(!empty($className) && class_exists($className))
		{
			$params = array();

			if(!empty($arguments))
			{
				parse_str($arguments, $params);
			}

			$trigger = new $className($this->container);

			if($trigger instanceof TriggerAbstract)
			{
				$trigger->execute($request, $params);
			}
			else
			{
				throw new \Exception('Trigger must be an instance of Fusio\TriggerAbstract');
			}
		}
		else
		{
			throw new \Exception('Class does not exist');
		}
	}

	public function getParameters()
	{
		return array(
			new Parameter\Text('class', 'Class'),
			new Parameter\Text('arguments', 'Arguments'),
		);
	}
}
