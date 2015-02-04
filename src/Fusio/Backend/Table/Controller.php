<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * Controller
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Controller extends TableAbstract
{
	public function getName()
	{
		return 'fusio_controller';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'class' => self::TYPE_VARCHAR,
			'config' => self::TYPE_ARRAY,
		);
	}
}
