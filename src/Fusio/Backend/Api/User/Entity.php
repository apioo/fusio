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

namespace Fusio\Backend\Api\User;

use Fusio\Authorization\ProtectionTrait;
use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Http\Exception as StatusCode;
use PSX\Loader\Context;

/**
 * Entity
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Entity extends SchemaApiAbstract
{
    use ProtectionTrait;
    use ValidatorTrait;

    /**
     * @Inject
     * @var \PSX\Data\Schema\SchemaManagerInterface
     */
    protected $schemaManager;

    /**
     * @Inject
     * @var \PSX\Sql\TableManager
     */
    protected $tableManager;

    /**
     * @return \PSX\Api\DocumentationInterface
     */
    public function getDocumentation()
    {
        $resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));

        $resource->addMethod(Resource\Factory::getMethod('GET')
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Backend\Schema\User'))
        );

        $resource->addMethod(Resource\Factory::getMethod('PUT')
            ->setRequest($this->schemaManager->getSchema('Fusio\Backend\Schema\User\Update'))
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Backend\Schema\Message'))
        );

        $resource->addMethod(Resource\Factory::getMethod('DELETE')
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Backend\Schema\Message'))
        );

        return new Documentation\Simple($resource);
    }

    /**
     * Returns the GET response
     *
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doGet(Version $version)
    {
        $userId = (int) $this->getUriFragment('user_id');
        $user   = $this->tableManager->getTable('Fusio\Backend\Table\User')->get($userId);

        if (!empty($user)) {
            $user['scopes'] = $this->tableManager
                ->getTable('Fusio\Backend\Table\User')
                ->getScopeNames($userId);

            return $user;
        } else {
            throw new StatusCode\NotFoundException('Could not find user');
        }
    }

    /**
     * Returns the POST response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doCreate(RecordInterface $record, Version $version)
    {
    }

    /**
     * Returns the PUT response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doUpdate(RecordInterface $record, Version $version)
    {
        $userId = (int) $this->getUriFragment('user_id');
        $user   = $this->tableManager->getTable('Fusio\Backend\Table\User')->get($userId);

        if (!empty($user)) {
            $this->getValidator()->validate($record);

            $this->tableManager->getTable('Fusio\Backend\Table\User')->update(array(
                'id'     => $user['id'],
                'status' => $record->getStatus(),
                'name'   => $record->getName(),
            ));

            $this->tableManager->getTable('Fusio\Backend\Table\User\Scope')->deleteAllFromUser($user['id']);

            $this->insertScopes($user['id'], $record->getScopes());

            return array(
                'success' => true,
                'message' => 'User successful updated',
            );
        } else {
            throw new StatusCode\NotFoundException('Could not find user');
        }
    }

    /**
     * Returns the DELETE response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doDelete(RecordInterface $record, Version $version)
    {
        $userId = (int) $this->getUriFragment('user_id');
        $user   = $this->tableManager->getTable('Fusio\Backend\Table\User')->get($userId);

        if (!empty($user)) {
            $this->tableManager->getTable('Fusio\Backend\Table\User')->delete(array(
                'id' => $user['id']
            ));

            $this->tableManager->getTable('Fusio\Backend\Table\User\Scope')->deleteAllFromUser($user['id']);

            return array(
                'success' => true,
                'message' => 'User successful deleted',
            );
        } else {
            throw new StatusCode\NotFoundException('Could not find user');
        }
    }

    protected function insertScopes($userId, $scopes)
    {
        if (!empty($scopes) && is_array($scopes)) {
            $scopeTable = $this->tableManager->getTable('Fusio\Backend\Table\User\Scope');
            $scopes     = $this->tableManager
                ->getTable('Fusio\Backend\Table\Scope')
                ->getByNames($scopes);

            foreach ($scopes as $scope) {
                $scopeTable->create(array(
                    'userId'  => $userId,
                    'scopeId' => $scope['id'],
                ));
            }
        }
    }
}
