<?php

namespace Fusio;

interface TriggerInterface
{
	/**
	 * Triggers an specific action from the given request
	 */
	public function execute(Request $request);
}
