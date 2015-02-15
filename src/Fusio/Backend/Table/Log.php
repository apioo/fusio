<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * Log
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Log extends TableAbstract
{
	public function getName()
	{
		return 'fusio_log';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'appId' => self::TYPE_INT,
			'routeId' => self::TYPE_INT,
			'ip' => self::TYPE_VARCHAR,
			'method' => self::TYPE_VARCHAR,
			'path' => self::TYPE_VARCHAR,
			'header' => self::TYPE_VARCHAR,
			'body' => self::TYPE_VARCHAR,
			'date' => self::TYPE_DATETIME,
		);
	}
}
