<?php

namespace Fusio;

use PSX\Data\RecordInterface;
use PSX\Http\Request;

interface TriggerInterface
{
	/**
	 * Triggers an specific action from the given request
	 *
	 * @param PSX\Data\RecordInterface $record
	 * @param array $parameters
	 * @param Fusio\Context $context
	 */
	public function execute(RecordInterface $record, array $parameters, Context $Context);

	/**
	 * Returns an array containing the fields which are needed by the trigger
	 *
	 * @return array
	 */
	public function getParameters();
}
