<?php

namespace Fusio;

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
			if($trigger->getType() == $type)
			{
				return $trigger;
			}
		}
	}
}
