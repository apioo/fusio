<?php

namespace Fusio\Backend\Api\Dashboard;

use DateTime;
use DateInterval;
use Fusio\Backend\Api\Authorization\ProtectionTrait;
use PSX\Controller\ApiAbstract;

/**
 * LatestRequests
 */
class LatestRequests extends ApiAbstract
{
	use ProtectionTrait;

	public function onGet()
	{
		$sql = '    SELECT path,
				           ip,
				           date
				      FROM fusio_log
				  ORDER BY date DESC
				     LIMIT 6';

		$result = $this->connection->fetchAll($sql);

		$this->setBody(array(
			'entry' => $result,
		));
	}
}

