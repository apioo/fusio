<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Impl\Table\App;

use DateTime;
use PSX\Sql\Condition;
use PSX\Sql\TableAbstract;

/**
 * Token
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Token extends TableAbstract
{
    const STATUS_ACTIVE  = 0x1;
    const STATUS_DELETED = 0x2;

    public function getName()
    {
        return 'fusio_app_token';
    }

    public function getColumns()
    {
        return array(
            'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
            'appId' => self::TYPE_INT,
            'userId' => self::TYPE_INT,
            'status' => self::TYPE_INT,
            'token' => self::TYPE_VARCHAR,
            'scope' => self::TYPE_VARCHAR,
            'ip' => self::TYPE_VARCHAR,
            'expire' => self::TYPE_DATETIME,
            'date' => self::TYPE_DATETIME,
        );
    }

    public function getTokensByApp($appId)
    {
        $now = new DateTime();
        $con = new Condition();
        $con->add('appId', '=', $appId);
        $con->add('status', '=', self::STATUS_ACTIVE);
        $con->add('expire', '>', $now->format('Y-m-d H:i:s'));

        return $this->getBy($con);
    }

    public function removeTokenFromApp($appId, $tokenId)
    {
        $sql = 'UPDATE fusio_app_token
				   SET status = :status
				 WHERE appId = :appId
				   AND id = :id';

        $this->connection->executeUpdate($sql, array(
            'status' => self::STATUS_DELETED,
            'appId'  => $appId,
            'id'     => $tokenId
        ));
    }

    public function removeAllTokensFromAppAndUser($appId, $userId)
    {
        $sql = 'UPDATE fusio_app_token
                   SET status = :status
                 WHERE appId = :appId
                   AND userId = :userId';

        $this->connection->executeUpdate($sql, array(
            'status' => self::STATUS_DELETED,
            'appId'  => $appId,
            'userId' => $userId
        ));
    }
}
