<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * Fields
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Fields extends TableAbstract
{
	public function getName()
	{
		return 'fusio_schema_fields';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'schema_id' => self::TYPE_INT,
			'name' => self::TYPE_VARCHAR,
			'type' => self::TYPE_VARCHAR,
		);
	}
}
