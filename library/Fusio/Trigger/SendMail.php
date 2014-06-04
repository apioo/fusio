<?php

namespace Fusio\Trigger;

use Fusio\Parameter;
use Fusio\TriggerAbstract;
use Fusio\Context;
use Fusio\Entity\Trigger;
use PSX\Data\RecordInterface;

class SendMail extends TriggerAbstract
{
	public function getName()
	{
		return Trigger::TYPE_MAIL_SEND;
	}

	public function execute(RecordInterface $record, array $parameters, Context $Context)
	{
	}

	public function getParameters()
	{
		return array(
			new Parameter\Text('receiver', 'Receiver'),
			new Parameter\Text('template_id', 'Template'),
		);
	}
}
