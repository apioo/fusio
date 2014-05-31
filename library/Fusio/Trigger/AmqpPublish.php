<?php

namespace Fusio\Trigger;

use Fusio\Parameter;
use Fusio\TriggerAbstract;
use PSX\Http\Request;

class AmqpPublish extends TriggerAbstract
{
	public function execute(Request $request, array $parameters)
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
