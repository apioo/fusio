<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace Fusio\Impl\Service\App;

use Fusio\Impl\Backend\Table\App as TableApp;
use Fusio\Impl\Backend\Table\App\Token as TableAppToken;
use Fusio\Impl\Backend\Table\User\Grant as TableUserGrant;
use Fusio\Impl\Service\App as ServiceApp;
use PSX\Data\ResultSet;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;
use PSX\Util\CurveArray;

/**
 * Grant
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Grant
{
    protected $appTable;
    protected $userGrantTable;
    protected $appTokenTable;

    public function __construct(TableApp $appTable, TableUserGrant $userGrantTable, TableAppToken $appTokenTable)
    {
        $this->appTable       = $appTable;
        $this->userGrantTable = $userGrantTable;
        $this->appTokenTable  = $appTokenTable;
    }

    public function getAll($userId)
    {
        return new ResultSet(
            null,
            null,
            null,
            CurveArray::nest($this->appTable->getAuthorizedApps($userId))
        );
    }

    public function delete($userId, $grantId)
    {
        $grant = $this->userGrantTable->get($grantId);

        if (!empty($grant)) {
            if ($grant['userId'] == $userId) {
                $this->userGrantTable->delete($grant);

                // delete tokens
                $this->appTokenTable->removeAllTokensFromAppAndUser($grant['appId'], $grant['userId']);
            } else {
                throw new StatusCode\BadRequestException('Invalid grant id');
            }
        } else {
            throw new StatusCode\NotFoundException('Could not find grant');
        }
    }
}
