<?php

namespace Fusio\Trigger;

use Fusio\Parameter;
use Fusio\TriggerAbstract;
use PSX\Http\Request;

class BeanstalkdPutPublish extends TriggerAbstract
{
	public function execute(Request $request, array $parameters)
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
