<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * User
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class User extends TableAbstract
{
	const STATUS_CONSUMER      = 0;
	const STATUS_ADMINISTRATOR = 1;
	const STATUS_DISABLED      = 2;

	public function getName()
	{
		return 'fusio_user';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'status' => self::TYPE_INT,
			'name' => self::TYPE_VARCHAR,
			'password' => self::TYPE_VARCHAR,
			'date' => self::TYPE_DATETIME,
		);
	}

	public function getScopeNames($userId)
	{
		$sql = '    SELECT scope.name 
				      FROM fusio_user_scope userScope 
				INNER JOIN fusio_scope scope 
				        ON scope.id = userScope.scopeId 
				     WHERE userScope.userId = :userId';

		$scopes = $this->connection->fetchAll($sql, array('userId' => $userId));
		$names  = array();

		foreach($scopes as $scope)
		{
			$names[] = $scope['name'];
		}

		return $names;
	}
}
