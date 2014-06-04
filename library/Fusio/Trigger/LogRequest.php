<?php

namespace Fusio\Trigger;

use Fusio\Parameter;
use Fusio\TriggerAbstract;
use Fusio\Context;
use Fusio\Entity\Trigger;
use PSX\Data\RecordInterface;

class LogRequest extends TriggerAbstract
{
	public function getName()
	{
		return Trigger::TYPE_LOG_REQUEST;
	}

	public function execute(RecordInterface $record, array $parameters, Context $Context)
	{
	}

	public function getParameters()
	{
		return array();
	}
}
