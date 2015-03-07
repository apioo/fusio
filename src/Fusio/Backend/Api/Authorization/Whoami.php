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

namespace Fusio\Backend\Api\Authorization;

use PSX\Controller\ApiAbstract;
use PSX\Http\Exception as StatusCode;

/**
 * Whoami
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Whoami extends ApiAbstract
{
	use ProtectionTrait;

	public function onGet()
	{
		$sql = 'SELECT id, 
				       status, 
				       name 
				  FROM fusio_user 
				 WHERE id = :id';

		$user = $this->connection->fetchAssoc($sql, array(
			'id' => $this->userId
		));

		if(!empty($user))
		{
			$this->setBody($user);
		}
		else
		{
			throw new StatusCode\UnauthorizedException('Not authenticated', 'Bearer');
		}
	}
}
