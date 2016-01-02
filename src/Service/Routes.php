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

namespace Fusio\Impl\Service;

use Fusio\Impl\Table\Routes as TableRoutes;
use Fusio\Impl\Table\Scope\Route as TableScopeRoute;
use Fusio\Impl\Service\Routes\DependencyManager;
use PSX\Api\Resource;
use PSX\Data\ResultSet;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;

/**
 * Routes
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Routes
{
    protected $routesTable;

    public function __construct(TableRoutes $routesTable, TableScopeRoute $scopeRoutesTable, DependencyManager $dependencyManager)
    {
        $this->routesTable       = $routesTable;
        $this->scopeRoutesTable  = $scopeRoutesTable;
        $this->dependencyManager = $dependencyManager;
    }

    public function getAll($startIndex = 0, $search = null)
    {
        $condition  = new Condition();
        $condition->equals('status', TableRoutes::STATUS_ACTIVE);
        $condition->notLike('path', '/backend%');
        $condition->notLike('path', '/consumer%');
        $condition->notLike('path', '/doc%');
        $condition->notLike('path', '/authorization%');
        $condition->notLike('path', '/export%');

        if (!empty($search)) {
            $condition->like('path', '%' . $search . '%');
        }

        $this->routesTable->setRestrictedFields(['config']);

        return new ResultSet(
            $this->routesTable->getCount($condition),
            $startIndex,
            16,
            $this->routesTable->getAll($startIndex, 16, 'id', Sql::SORT_DESC, $condition)
        );
    }

    public function get($routeId)
    {
        $route = $this->routesTable->get($routeId);

        if (!empty($route)) {
            if ($route['status'] == TableRoutes::STATUS_DELETED) {
                throw new StatusCode\GoneException('Route was deleted');
            }

            return $route;
        } else {
            throw new StatusCode\NotFoundException('Could not find route');
        }
    }

    public function create($methods, $path, $config)
    {
        $this->routesTable->create(array(
            'methods'    => $methods,
            'path'       => $path,
            'controller' => 'Fusio\Impl\Controller\SchemaApiController',
            'config'     => $config,
        ));

        // get last insert id
        $routeId = $this->routesTable->getLastInsertId();

        // insert dependency links
        $this->dependencyManager->insertDependencyLinks($routeId, $config);

        // lock dependencies
        $this->dependencyManager->lockExistingDependencies($routeId);
    }

    public function update($routeId, $methods, $path, $config)
    {
        $route = $this->routesTable->get($routeId);

        if (!empty($route)) {
            if ($route['status'] == TableRoutes::STATUS_DELETED) {
                throw new StatusCode\GoneException('Route was deleted');
            }

            $this->routesTable->update(array(
                'id'         => $route->getId(),
                'methods'    => $methods,
                'path'       => $path,
                'controller' => 'Fusio\Impl\Controller\SchemaApiController',
                'config'     => $config,
            ));

            // remove all dependency links
            $this->dependencyManager->removeExistingDependencyLinks($route->getId());

            // unlock dependencies
            $this->dependencyManager->unlockExistingDependencies($route->getId());

            // insert dependency links
            $this->dependencyManager->insertDependencyLinks($route->getId(), $config);

            // lock dependencies
            $this->dependencyManager->lockExistingDependencies($route->getId());
        } else {
            throw new StatusCode\NotFoundException('Could not find route');
        }
    }

    public function delete($routeId)
    {
        $route = $this->routesTable->get($routeId);

        if (!empty($route)) {
            if ($route['status'] == TableRoutes::STATUS_DELETED) {
                throw new StatusCode\GoneException('Route was deleted');
            }

            // check whether route has a production version
            if ($this->hasProductionVersion($route->getConfig())) {
                throw new StatusCode\ConflictException('It is not possible to delete a route which contains a production version');
            }

            // unlock dependencies
            $this->dependencyManager->unlockExistingDependencies($route->getId());

            // delete route
            $this->routesTable->update(array(
                'id'     => $route->getId(),
                'status' => TableRoutes::STATUS_DELETED
            ));
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
