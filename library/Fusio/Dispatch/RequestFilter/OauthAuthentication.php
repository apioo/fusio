<?php
/*
 * amun
 * A social content managment system based on the psx framework. For
 * the current version and informations visit <http://amun.phpsx.org>
 *
 * Copyright (c) 2010-2013 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of amun. amun is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * amun is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with amun. If not, see <http://www.gnu.org/licenses/>.
 */


namespace Fusio\Dispatch\RequestFilter;

use DateTime;
use DateInterval;
use Closure;
use PSX\Exception;
use PSX\Oauth;
use PSX\Oauth\Provider\Data\Consumer;
use PSX\Sql;
use PSX\Sql\Condition;

/**
 * OauthAuthentication
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class OauthAuthentication extends \PSX\Dispatch\RequestFilter\OauthAuthentication
{
	protected $sql;

	protected $claimedUserId;
	protected $accessId;
	protected $requestToken;

	public function __construct(Sql $sql)
	{
		$auth = $this;
		parent::__construct(function($consumerKey, $token) use ($auth){
			return $auth->getConsumer($consumerKey, $token);
		});

		$this->sql = $sql;
	}

	public function getConsumer($consumerKey, $token)
	{
		$sql = <<<SQL
SELECT
	`api`.`id`             AS `apiId`,
	`api`.`consumerKey`    AS `apiConsumerKey`,
	`api`.`consumerSecret` AS `apiConsumerSecret`
FROM 
	{$this->registry['table.oauth']} api
WHERE 
	`api`.`consumerKey` = ?
LIMIT 1
SQL;

		$row = $this->sql->getRow($sql, array($consumerKey));

		if(!empty($row))
		{
			$request = $this->fetchRequestValues($token);

			// check whether the request token was requested from the same ip
			if($request['requestIp'] != $_SERVER['REMOTE_ADDR'])
			{
				// we can not do this so strictly because most applications
				// changes often the ip
				//throw new Exception('Token was requested from another ip');
			}

			// check whether the request is assigned to this application
			if($row['apiId'] != $request['requestApiId'])
			{
				throw new Exception('Request is not assigned to this application');
			}

			// check expire
			$now  = new DateTime('NOW', $this->registry['core.default_timezone']);
			$date = new DateTime($request['requestDate'], $this->registry['core.default_timezone']);
			$date->add(new DateInterval($request['requestExpire']));

			if($now > $date)
			{
				$this->sql->delete($this->registry['table.oauth_request'], new Condition(array('token', '=', $token)));

				throw new Exception('The token is expired');
			}

			$this->claimedUserId = $request['requestUserId'];
			$this->requestToken  = $request['requestToken'];

			return new Consumer($row['apiConsumerKey'], $row['apiConsumerSecret'], $request['requestToken'], $request['requestTokenSecret']);
		}
		else
		{
			throw new Exception('Invalid consumer key');
		}
	}

	protected function fetchRequestValues($token)
	{
		$sql = <<<SQL
SELECT
	`request`.`apiId`       AS `requestApiId`,
	`request`.`userId`      AS `requestUserId`,
	`request`.`ip`          AS `requestIp`,
	`request`.`nonce`       AS `requestNonce`,
	`request`.`token`       AS `requestToken`,
	`request`.`tokenSecret` AS `requestTokenSecret`,
	`request`.`expire`      AS `requestExpire`,
	`request`.`date`        AS `requestDate`
FROM 
	{$this->registry['table.oauth_request']} `request`
WHERE 
	`request`.`token` = ?
AND 
	`request`.`status` = ?
LIMIT 1
SQL;

		$row = $this->sql->getRow($sql, array($token, Record::ACCESS));

		if(!empty($row))
		{
			// check whether request is allowed
			$sql = <<<SQL
SELECT
	`access`.`id`      AS `accessId`,
	`access`.`allowed` AS `accessAllowed`
FROM 
	{$this->registry['table.oauth_access']} `access`
WHERE 
	`access`.`apiId` = ?
AND 
	`access`.`userId` = ?
SQL;

			$access = $this->sql->getRow($sql, array($row['requestApiId'], $row['requestUserId']));

			if(!empty($access))
			{
				if($access['accessAllowed'] === '1')
				{
					$this->accessId = $access['accessId'];

					return $row;
				}
				else
				{
					throw new Exception('Access was rejected');
				}
			}
			else
			{
				throw new Exception('No access available');
			}
		}
		else
		{
			throw new Exception('Invalid request');
		}
	}

	protected function callSuccess()
	{
		call_user_func_array($this->successCallback, array($this->claimedUserId, $this->accessId, $this->requestToken));
	}
}
