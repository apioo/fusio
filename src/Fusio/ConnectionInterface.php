<?php

namespace Fusio\Connection;

use Fusio\ConnectionInterface;

interface ConnectionInterface
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

	/**
	 * Returns the form where the user can configure the connection
	 *
	 * @return Fusio\Form\Container
	 */
	public function getForm();
}
