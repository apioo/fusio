<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * App
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class App extends TableAbstract
{
	public function getName()
	{
		return 'fusio_app';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'status' => self::TYPE_INT,
			'name' => self::TYPE_VARCHAR,
			'url' => self::TYPE_VARCHAR,
			'appKey' => self::TYPE_VARCHAR,
			'appSecret' => self::TYPE_VARCHAR,
			'date' => self::TYPE_DATETIME,
		);
	}
}
