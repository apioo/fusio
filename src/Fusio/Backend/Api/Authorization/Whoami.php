<?php

namespace Fusio\Backend\Api\Authorization;

use PSX\Controller\ApiAbstract;
use PSX\Http\Exception as StatusCode;

/**
 * Whoami
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
