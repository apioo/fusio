<?php

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;
use PSX\Sql\Condition;

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

	public function getByUser($userId)
	{
		$sql = '    SELECT id, 
				           name 
				      FROM fusio_user_scope userScope 
				INNER JOIN fusio_scope user 
				        ON scope.id = userScope.scopeId 
				     WHERE userScope.userId = :userId';

		return $this->project($sql, array('userId' => $userId));
	}

	public function getByApp($appId)
	{
		$sql = '    SELECT id, 
				           name 
				      FROM fusio_app_scope appScope 
				INNER JOIN fusio_scope scope 
				        ON scope.id = appScope.scopeId 
				     WHERE appScope.appId = :appId';

		return $this->project($sql, array('appId' => $appId));
	}

	public function getByNames(array $names)
	{
		return $this->getAll(0, 1024, null, null, new Condition(['name', 'IN', array_filter($names)]));
	}
}
