<?php

namespace Fusio\Connection;

interface FactoryInterface
{
	/**
	 * Returns the connector which can be used to query or manipulate
	 *
	 * @return Fusio\Connection\ConnectorInterface
	 */
	public function getConnector(array $params);

	/**
	 * Returns an array containing the fields which are needed by the connection
	 *
	 * @return array
	 */
	public function getParameters();
}
