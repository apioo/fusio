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

namespace Fusio\Impl\Backend\Api\App;

use Fusio\Impl\Authorization\ProtectionTrait;
use PSX\Controller\ApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Http\Exception\NotFoundException;

/**
 * Token
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Token extends ApiAbstract
{
    use ProtectionTrait;

    /**
     * @Inject
     * @var \PSX\Sql\TableManager
     */
    protected $tableManager;

    public function doRemove()
    {
        $appId   = $this->getUriFragment('app_id');
        $tokenId = $this->getUriFragment('token_id');

        $app = $this->tableManager->getTable('Fusio\Impl\Backend\Table\App')->get($appId);

        if ($app instanceof RecordInterface) {
            $this->tableManager->getTable('Fusio\Impl\Backend\Table\App\Token')->removeTokenFromApp($appId, $tokenId);

            $this->setBody(array(
                'success' => true,
                'message' => 'Removed token successful',
            ));
        } else {
            throw new NotFoundException('Invalid app');
        }
    }
}
