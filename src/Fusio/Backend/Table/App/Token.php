<?php

namespace Fusio\Backend\Table\App;

use PSX\Sql\TableAbstract;

/**
 * Token
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Token extends TableAbstract
{
	public function getName()
	{
		return 'fusio_app_token';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'appId' => self::TYPE_INT,
			'userId' => self::TYPE_INT,
			'token' => self::TYPE_VARCHAR,
			'scope' => self::TYPE_VARCHAR,
			'ip' => self::TYPE_VARCHAR,
			'expire' => self::TYPE_INT,
			'date' => self::TYPE_DATETIME,
		);
	}
}
