<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * User
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class User extends TableAbstract
{
	public function getName()
	{
		return 'fusio_user';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'status' => self::TYPE_INT,
			'name' => self::TYPE_VARCHAR,
			'password' => self::TYPE_VARCHAR,
			'date' => self::TYPE_DATETIME,
		);
	}
}
