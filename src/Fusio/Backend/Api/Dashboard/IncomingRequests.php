<?php

namespace Fusio\Backend\Api\Dashboard;

use DateTime;
use DateInterval;
use Fusio\Backend\Api\Authorization\ProtectionTrait;
use PSX\Controller\ApiAbstract;

/**
 * IncomingRequests
 */
class IncomingRequests extends ApiAbstract
{
	use ProtectionTrait;

	const PAST_DAYS = 9;

	public function onGet()
	{
		$past = new DateTime();
		$past->sub(new DateInterval('P' . self::PAST_DAYS. 'D'));

		$labels = array();
		$data   = array();

		for($i = 0; $i <= self::PAST_DAYS; $i++)
		{
			$sql = 'SELECT COUNT(id) as count
					  FROM fusio_log 
					 WHERE DATE(date) = :date';

			$count = $this->connection->fetchColumn($sql, array('date' => $past->format('Y-m-d')));

			$data[]   = (int) $count;
			$labels[] = $past->format('d.m');

			$past->add(new DateInterval('P1D'));
		}

		$this->setBody(array(
			'labels' => $labels,
			'data'   => [$data],
			'series' => ['Requests'],
		));
	}
}

