<?php

namespace Fusio\Trigger;

use Fusio\Parameter;
use Fusio\TriggerAbstract;
use Fusio\Context;
use Fusio\Entity\Trigger;
use PSX\Data\RecordInterface;

class BeanstalkdPutPublish extends TriggerAbstract
{
	public function getName()
	{
		return Trigger::TYPE_MQ_BEANSTALKD;
	}

	public function execute(RecordInterface $record, array $parameters, Context $Context)
	{
	}

	public function getParameters()
	{
		return array(
			new Parameter\Text('host', 'Host'),
			new Parameter\Text('port', 'Port'),
			new Parameter\Text('queue_name', 'Queue name'),
		);
	}
}
