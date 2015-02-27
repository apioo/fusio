<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * Scope
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Scope extends TableAbstract
{
	public function getName()
	{
		return 'fusio_scope';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'name' => self::TYPE_VARCHAR,
		);
	}
}
