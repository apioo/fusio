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

namespace Fusio\Impl\Backend\Api\Account;

use Fusio\Impl\Authorization\ProtectionTrait;
use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Http\Exception as StatusCode;
use PSX\Loader\Context;

/**
 * ChangePassword
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ChangePassword extends SchemaApiAbstract
{
    use ProtectionTrait;

    /**
     * @Inject
     * @var \PSX\Data\Schema\SchemaManagerInterface
     */
    protected $schemaManager;

    /**
     * @Inject
     * @var \Fusio\Impl\Service\User
     */
    protected $userService;

    /**
     * @return \PSX\Api\DocumentationInterface
     */
    public function getDocumentation()
    {
        $resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));

        $resource->addMethod(Resource\Factory::getMethod('PUT')
            ->setRequest($this->schemaManager->getSchema('Fusio\Impl\Backend\Schema\Account\ChangePassword'))
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Impl\Backend\Schema\Message'))
        );

        return new Documentation\Simple($resource);
    }

    /**
     * Returns the PUT response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doPut(RecordInterface $record, Version $version)
    {
        $this->userService->changePassword(
            $this->userId, 
            $this->appId, 
            $record->getOldPassword(), 
            $record->getNewPassword(),
            $record->getVerifyPassword()
        );

        return array(
            'success' => true,
            'message' => 'Password successful changed',
        );
    }
}
