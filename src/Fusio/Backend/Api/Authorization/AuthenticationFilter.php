<?php

namespace Fusio\Backend\Api\Authorization;

use Closure;
use Doctrine\DBAL\Connection;
use PSX\Dispatch\Filter\Oauth2Authentication;

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
