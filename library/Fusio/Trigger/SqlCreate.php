<?php

namespace Fusio\Trigger;

use Fusio\Context;
use Fusio\Entity\Trigger;
use PSX\Data\RecordInterface;

class SqlCreate extends SqlOperationAbstract
{
	public function getName()
	{
		return Trigger::TYPE_SQL_CREATE;
	}

	public function execute(RecordInterface $record, array $parameters, Context $Context)
	{
	}
}
