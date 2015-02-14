<?php

namespace Fusio;

interface ConnectionInterface extends ConfigurableInterface
{
	/**
	 * Returns an arbitrary connection to a system. This can be i.e. an mysql
	 * or mongodb connection. The connection can then be used by an action to
	 * fulfill the task. The $config contains parameters which were set by the
	 * user
	 *
	 * @param array $config
	 * @return mixed
	 */
	public function getConnection(Parameters $config);
}
