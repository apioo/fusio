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

namespace Fusio\Impl\Backend\Api\Routes;

use Fusio\Impl\Authorization\ProtectionTrait;
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
     * @Inject
     * @var \Fusio\Impl\Backend\Table\Routes\DependencyManager
     */
    protected $routesDependencyManager;

    /**
     * @return \PSX\Api\DocumentationInterface
     */
    public function getDocumentation()
    {
        $resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));

        $resource->addMethod(Resource\Factory::getMethod('GET')
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Impl\Backend\Schema\Routes'))
        );

        $resource->addMethod(Resource\Factory::getMethod('PUT')
            ->setRequest($this->schemaManager->getSchema('Fusio\Impl\Backend\Schema\Routes\Update'))
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
        $routeId = (int) $this->getUriFragment('route_id');
        $route   = $this->tableManager->getTable('Fusio\Impl\Backend\Table\Routes')->get($routeId);

        if (!empty($route)) {
            return $route;
        } else {
            throw new StatusCode\NotFoundException('Could not find route');
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
        $routeId = (int) $this->getUriFragment('route_id');
        $route   = $this->tableManager->getTable('Fusio\Impl\Backend\Table\Routes')->get($routeId);

        if (!empty($route)) {
            $this->tableManager->getTable('Fusio\Impl\Backend\Table\Routes')->update(array(
                'id'         => $route->getId(),
                'methods'    => $record->getMethods(),
                'path'       => $record->getPath(),
                'controller' => 'Fusio\Impl\Controller\SchemaApiController',
                'config'     => $record->getConfig(),
            ));

            // remove all dependency links
            $this->routesDependencyManager->removeExistingDependencyLinks($route->getId());

            // unlock dependencies
            $this->routesDependencyManager->unlockExistingDependencies($route->getId());

            // insert dependency links
            $this->routesDependencyManager->insertDependencyLinks($route->getId(), $record->getConfig());

            // lock dependencies
            $this->routesDependencyManager->lockExistingDependencies($route->getId());

            return array(
                'success' => true,
                'message' => 'Routes successful updated',
            );
        } else {
            throw new StatusCode\NotFoundException('Could not find route');
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
        $routeId = (int) $this->getUriFragment('route_id');
        $route   = $this->tableManager->getTable('Fusio\Impl\Backend\Table\Routes')->get($routeId);

        if (!empty($route)) {
            // check whether route has a production version
            if ($this->hasProductionVersion($route->getConfig())) {
                throw new StatusCode\ConflictException('It is not possible to delete a route which contains a production version');
            }

            // remove all dependency links
            $this->routesDependencyManager->removeExistingDependencyLinks($route->getId());

            // unlock dependencies
            $this->routesDependencyManager->unlockExistingDependencies($route->getId());

            // remove all scope routes
            $this->tableManager->getTable('Fusio\Impl\Backend\Table\Scope\Route')->deleteAllFromRoute($route->getId());

            // delete route
            $this->tableManager->getTable('Fusio\Impl\Backend\Table\Routes')->delete(array(
                'id' => $route->getId(),
            ));

            return array(
                'success' => true,
                'message' => 'Routes successful deleted',
            );
        } else {
            throw new StatusCode\NotFoundException('Could not find route');
        }
    }

    protected function hasProductionVersion(array $config)
    {
        foreach ($config as $version) {
            if ($version->getActive() && in_array($version->getStatus(), [Resource::STATUS_ACTIVE, Resource::STATUS_DEPRECATED])) {
                return true;
            }
        }

        return false;
    }
}
