<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;
use PSX\Sql\Condition;

/**
 * Schema
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Schema extends TableAbstract
{
	public function getName()
	{
		return 'fusio_schema';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'extendsId' => self::TYPE_INT,
			'name' => self::TYPE_VARCHAR,
			'propertyName' => self::TYPE_VARCHAR,
		);
	}
}
