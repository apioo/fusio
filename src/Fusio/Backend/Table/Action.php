<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * Action
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Action extends TableAbstract
{
	public function getName()
	{
		return 'fusio_action';
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
