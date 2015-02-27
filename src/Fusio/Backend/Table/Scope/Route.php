<?php

namespace Fusio\Backend\Table\Scope;

use PSX\Sql\TableAbstract;

/**
 * Route
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Route extends TableAbstract
{
	public function getName()
	{
		return 'fusio_scope_routes';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'scopeId' => self::TYPE_INT,
			'routeId' => self::TYPE_INT,
			'allow' => self::TYPE_INT,
			'methods' => self::TYPE_VARCHAR,
		);
	}

	public function deleteAllFromScope($scopeId)
	{
		$sql = 'DELETE FROM fusio_scope_routes
				      WHERE scopeId = :id';

		$this->connection->executeQuery($sql, array('id' => $scopeId));
	}
}
