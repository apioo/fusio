<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * Routes
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Routes extends TableAbstract
{
	public function getName()
	{
		return 'fusio_routes';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'methods' => self::TYPE_VARCHAR,
			'path' => self::TYPE_VARCHAR,
			'controller' => self::TYPE_VARCHAR,
			'config' => self::TYPE_ARRAY,
		);
	}
}
