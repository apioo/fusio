<?php

namespace Fusio\Trigger;

use Fusio\Context;
use Fusio\Entity\Trigger;
use PSX\Data\RecordInterface;

class SqlDelete extends SqlOperationAbstract
{
	public function getName()
	{
		return Trigger::TYPE_SQL_DELETE;
	}

	public function execute(RecordInterface $record, array $parameters, Context $Context)
	{
	}
}
