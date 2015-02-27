<?php

namespace Fusio\Backend\Api\Authorization;

use PSX\Dispatch\Filter\Condition\RequestMethodChoice;

trait ProtectionTrait
{
	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * ID of the authenticated user
	 *
	 * @var integer
	 */
	protected $userId;

	public function getPreFilter()
	{
		return [new AuthenticationFilter($this->connection, function($userId){

			$this->userId = $userId;

		})];
	}
}
