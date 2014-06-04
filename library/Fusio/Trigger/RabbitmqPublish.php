<?php

namespace Fusio\Trigger;

use Fusio\Parameter;
use Fusio\TriggerAbstract;
use Fusio\Context;
use PSX\Data\RecordInterface;

class RabbitmqPublish extends TriggerAbstract
{
	public function getName()
	{
		return Trigger::TYPE_MQ_RABBITMQ;
	}

	public function execute(RecordInterface $record, array $parameters, Context $Context)
	{
	}

	public function getParameters()
	{
		return array(
			new Parameter\Text('host', 'Host'),
			new Parameter\Text('port', 'Port'),
			new Parameter\Text('user', 'User'),
			new Parameter\Text('pw', 'Password'),
			new Parameter\Text('vhost', 'VHost'),
			new Parameter\Text('exchange_name', 'Exchange name'),
			new Parameter\Text('queue_name', 'Queue name'),
		);
	}
}
