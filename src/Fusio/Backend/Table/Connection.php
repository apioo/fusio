<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * Connection
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Connection extends TableAbstract
{
	public function getName()
	{
		return 'fusio_connection';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'name' => self::TYPE_VARCHAR,
			'class' => self::TYPE_VARCHAR,
			'config' => self::TYPE_ARRAY,
		);
	}
}
