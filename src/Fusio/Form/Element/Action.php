<?php

namespace Fusio\Form\Element;

use Doctrine\DBAL\Connection as DBALConnection;

class Action extends Select
{
	protected $_connection;

	public function __construct($name, $title, DBALConnection $connection)
	{
		parent::__construct($name, $title);

		$this->_connection = $connection;

		$this->load();
	}

	protected function load()
	{
		$result = $this->_connection->fetchAll('SELECT id, name FROM fusio_action ORDER BY name ASC');

		foreach($result as $row)
		{
			$this->addOption((int) $row['id'], $row['name']);
		}
	}
}
