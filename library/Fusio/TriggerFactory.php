<?php

namespace Fusio;

use PSX\Http\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TriggerFactory
{
	protected $triggers = array();

	public function registerTrigger(TriggerAbstract $trigger)
	{
		$this->triggers[] = $trigger;
	}

	public function factory($type)
	{
		foreach($this->triggers as $trigger)
		{
			if($trigger->getType() == $trigger)
			{
				return $trigger;
			}
		}
	}
}
