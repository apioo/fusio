<?php

namespace Fusio;

interface ConnectionInterface
{
	/**
	 * Connects to the service with the given parameter and returns the handler
	 * which can be used by an datasource
	 *
	 * @return mixed
	 */
	public function connect(array $params);

	/**
	 * Returns an array containing the fields which are needed by the connection
	 *
	 * @return array
	 */
	public static function getConnectionParameters();

	/**
	 * Returns an array containing the fields which are needed by the datasource
	 *
	 * @return array
	 */
	public static function getDatasourceParameters();
}
