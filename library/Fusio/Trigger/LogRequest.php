<?php

namespace Fusio\Trigger;

use Fusio\Parameter;
use Fusio\TriggerAbstract;
use Fusio\Context;
use PSX\Data\RecordInterface;

class LogRequest extends TriggerAbstract
{
	public function execute(RecordInterface $record, array $parameters, Context $Context)
	{
	}

	public function getParameters()
	{
		return array();
	}
}
