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

use Closure;
use Doctrine\DBAL\Connection;
use PSX\Dispatch\Filter\Oauth2Authentication;

/**
 * AuthenticationFilter
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class AuthenticationFilter extends Oauth2Authentication
{
	protected $connection;

	public function __construct(Connection $connection, Closure $callback)
	{
		parent::__construct(function($accessToken){
			return $this->validate($accessToken);
		});

		$this->connection = $connection;
		$this->callback   = $callback;
	}

	protected function validate($accessToken)
	{
		$sql = 'SELECT userId,
				       token,
				       scope,
				       expire,
				       date
				  FROM fusio_app_token
				 WHERE token = :token';

		$token = $this->connection->fetchAssoc($sql, array('token' => $accessToken));

		if(!empty($token))
		{
			// check scope
			$scopes = explode(' ', $token['scope']);
			if(!in_array('backend', $scopes))
			{
				return false;
			}

			// check expire
			if(time() > $token['expire'])
			{
				return false;
			}

			// call the callback so that the controller knows the assigned user.
			// Instead of an id this could also be an user object or something  
			// else which represents the user
			call_user_func($this->callback, $token['userId']);

			return true;
		}

		return false;
	}
}
