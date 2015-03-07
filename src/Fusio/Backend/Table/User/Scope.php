<?php

namespace Fusio\Backend\Table\User;

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
		return 'fusio_user_scope';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'userId' => self::TYPE_INT,
			'scopeId' => self::TYPE_INT,
		);
	}

	public function deleteAllFromUser($userId)
	{
		$sql = 'DELETE FROM fusio_user_scope
				      WHERE userId = :id';

		$this->connection->executeQuery($sql, array('id' => $userId));
	}
}
