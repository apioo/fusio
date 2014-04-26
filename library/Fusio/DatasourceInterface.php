<?php

namespace Fusio;

interface DatasourceInterface
{
	/**
	 * Returns the handler which operates on the datasource
	 *
	 * @return PSX\Handler\HandlerInterface
	 */
	public function getHandler();
}
