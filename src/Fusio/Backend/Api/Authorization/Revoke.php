<?php

namespace Fusio\Backend\Api\Authorization;

use PSX\Controller\ApiAbstract;
use PSX\Http\Exception as StatusCode;

/**
 * Revoke
 */
class Revoke extends ApiAbstract
{
	use ProtectionTrait;

	public function onPost()
	{
		$header = $this->getHeader('Authorization');
		$parts  = explode(' ', $header, 2);
		$type   = isset($parts[0]) ? $parts[0] : null;
		$token  = isset($parts[1]) ? $parts[1] : null;

		if($type == 'Bearer')
		{
			$sql = 'SELECT id, 
					       userId,
					       scope
					  FROM fusio_app_token 
					 WHERE token = :token';

			$row = $this->connection->fetchAssoc($sql, array('token' => $token));

			// the token must be assigned to the user and must have an backend
			// scope so we can invalidate only tokens for the backend
			if(!empty($row) && $row['userId'] == $this->userId && strpos($row['scope'], 'backend') !== false)
			{
				$sql = 'DELETE FROM fusio_app_token 
						      WHERE id = :id';

				$this->connection->executeUpdate($sql, array('id' => $row['id']));
			}
			else
			{
				throw new StatusCode\BadRequestException('Invalid token');
			}
		}
		else
		{
			throw new StatusCode\BadRequestException('Invalid token type');
		}
	}
}
