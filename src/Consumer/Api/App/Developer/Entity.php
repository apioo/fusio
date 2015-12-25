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

namespace Fusio\Impl\Consumer\Api\App\Developer;

use Fusio\Impl\Authorization\ProtectionTrait;
use Fusio\Impl\Backend\Api\App\ValidatorTrait;
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
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Impl\Backend\Schema\App'))
        );

        $resource->addMethod(Resource\Factory::getMethod('PUT')
            ->setRequest($this->schemaManager->getSchema('Fusio\Impl\Backend\Schema\App\Update'))
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Impl\Backend\Schema\Message'))
        );

        $resource->addMethod(Resource\Factory::getMethod('DELETE')
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Impl\Backend\Schema\Message'))
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
        $appId = (int) $this->getUriFragment('app_id');
        $app   = $this->tableManager->getTable('Fusio\Impl\Backend\Table\App')->get($appId);

        if (!empty($app)) {
            if ($app['userId'] != $this->userId) {
                throw new StatusCode\BadRequestException('App does not belong to the user');
            }

            $app['scopes'] = $this->tableManager->getTable('Fusio\Impl\Backend\Table\Scope')
                ->getByApp($app['id']);

            $app['tokens'] = $this->tableManager->getTable('Fusio\Impl\Backend\Table\App\Token')
                ->getTokensByApp($app['id']);

            return $app;
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
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
        $appId = (int) $this->getUriFragment('app_id');
        $app   = $this->tableManager->getTable('Fusio\Impl\Backend\Table\App')->get($appId);

        if (!empty($app)) {
            if ($app['userId'] != $this->userId) {
                throw new StatusCode\BadRequestException('App does not belong to the user');
            }

            $this->tableManager->getTable('Fusio\Impl\Backend\Table\App')->update(array(
                'id'     => $app->getId(),
                'status' => $record->getStatus(),
                'name'   => $record->getName(),
                'url'    => $record->getUrl(),
            ));

            $this->tableManager->getTable('Fusio\Impl\Backend\Table\App\Scope')->deleteAllFromApp($appId);

            $scopes = $record->getScopes();

            if (!empty($scopes) && is_array($scopes)) {
                $this->insertScopes($appId, $scopes);
            }

            return array(
                'success' => true,
                'message' => 'App successful updated',
            );
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
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
        $appId = (int) $this->getUriFragment('app_id');
        $app   = $this->tableManager->getTable('Fusio\Impl\Backend\Table\App')->get($appId);

        if (!empty($app)) {
            if ($app['userId'] != $this->userId) {
                throw new StatusCode\BadRequestException('App does not belong to the user');
            }

            $this->tableManager->getTable('Fusio\Impl\Backend\Table\App\Scope')->deleteAllFromApp($appId);

            $this->tableManager->getTable('Fusio\Impl\Backend\Table\App')->delete(array(
                'id' => $app['id']
            ));

            return array(
                'success' => true,
                'message' => 'App successful deleted',
            );
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
        }
    }

    protected function insertScopes($appId, $scopes)
    {
        $scopes = $this->tableManager->getTable('Fusio\Impl\Backend\Table\Scope')->getByNames($scopes);
        $table  = $this->tableManager->getTable('Fusio\Impl\Backend\Table\App\Scope');

        foreach ($scopes as $scope) {
            $table->create(array(
                'appId'   => $appId,
                'scopeId' => $scope['id'],
            ));
        }
    }
}
