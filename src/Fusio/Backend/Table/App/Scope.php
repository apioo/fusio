<?php

namespace Fusio\Backend\Table\App;

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
		return 'fusio_app_scope';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'appId' => self::TYPE_INT,
			'scopeId' => self::TYPE_INT,
		);
	}

	public function getScopesByApp()
	{
		
	}
}
