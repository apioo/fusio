<?php

namespace Fusio\Backend\Api\Dashboard;

use DateTime;
use DateInterval;
use Fusio\Backend\Api\Authorization\ProtectionTrait;
use PSX\Controller\ApiAbstract;

/**
 * MostUsedApps
 */
class MostUsedApps extends ApiAbstract
{
	use ProtectionTrait;

	public function onGet()
	{
		$sql = '    SELECT COUNT(log.id) AS count,
				           app.name
				      FROM fusio_log log
				INNER JOIN fusio_app app
				        ON log.appId = app.id
				     WHERE log.date > DATE_SUB(NOW(), INTERVAL 1 MONTH) 
				  GROUP BY log.routeId
				  ORDER BY count DESC
				     LIMIT 6';

		$result = $this->connection->fetchAll($sql);

		$this->setBody(array(
			'entry' => $result,
		));
	}
}

