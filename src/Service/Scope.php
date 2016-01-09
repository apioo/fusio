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

namespace Fusio\Impl\Service;

use Fusio\Impl\Table\App\Scope as TableAppScope;
use Fusio\Impl\Table\Scope as TableScope;
use Fusio\Impl\Table\Scope\Route as TableScopeRoute;
use Fusio\Impl\Table\User\Scope as TableUserScope;
use PSX\Data\ResultSet;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;

/**
 * Scope
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Scope
{
    protected $scopeTable;
    protected $scopeRouteTable;
    protected $appScopeTable;
    protected $userScopeTable;

    public function __construct(TableScope $scopeTable, TableScopeRoute $scopeRouteTable, TableAppScope $appScopeTable, TableUserScope $userScopeTable)
    {
        $this->scopeTable      = $scopeTable;
        $this->scopeRouteTable = $scopeRouteTable;
        $this->appScopeTable   = $appScopeTable;
        $this->userScopeTable  = $userScopeTable;
    }

    public function getAll($startIndex = 0, $search = null)
    {
        $condition = !empty($search) ? new Condition(['name', 'LIKE', '%' . $search . '%']) : null;

        return new ResultSet(
            $this->scopeTable->getCount($condition),
            $startIndex,
            16,
            $this->scopeTable->getAll($startIndex, 16, 'id', Sql::SORT_DESC, $condition)
        );
    }

    public function getByUser($userId, $startIndex = 0)
    {
        return new ResultSet(
            null,
            null,
            null,
            $this->userScopeTable->getByUser($userId)
        );
    }

    public function get($scopeId)
    {
        $scope = $this->scopeTable->get($scopeId);

        if (!empty($scope)) {
            $scope['routes'] = $this->scopeRouteTable->getByScopeId($scope['id']);

            return $scope;
        } else {
            throw new StatusCode\NotFoundException('Could not find scope');
        }
    }

    public function create($name, $description, array $routes = null)
    {
        $this->scopeTable->create(array(
            'name'        => $name,
            'description' => $description,
        ));

        // insert routes
        $scopeId = $this->scopeTable->getLastInsertId();

        $this->insertRoutes($scopeId, $routes);
    }

    public function update($scopeId, $name, $description, array $routes = null)
    {
        $scope = $this->scopeTable->get($scopeId);

        if (!empty($scope)) {
            $this->scopeTable->update(array(
                'id'          => $scope['id'],
                'name'        => $name,
                'description' => $description,
            ));

            $this->scopeRouteTable->deleteAllFromScope($scope['id']);

            $this->insertRoutes($scope['id'], $routes);
        } else {
            throw new StatusCode\NotFoundException('Could not find scope');
        }
    }

    public function delete($scopeId)
    {
        $scope = $this->scopeTable->get($scopeId);

        if (!empty($scope)) {
            // check whether the scope is used by an app or user
            $appScopes = $this->appScopeTable->getCount(new Condition(['scopeId', '=', $scope['id']]));
            if ($appScopes > 0) {
                throw new StatusCode\ConflictException('Scope is assigned to an app. Remove the scope from the app in order to delete the scope');
            }

            $userScopes = $this->userScopeTable->getCount(new Condition(['scopeId', '=', $scope['id']]));
            if ($userScopes > 0) {
                throw new StatusCode\ConflictException('Scope is assgined to an user. Remove the scope from the user in order to delete the scope');
            }

            // delete all routes assigned to the scope
            $this->scopeRouteTable->deleteAllFromScope($scope['id']);

            $this->scopeTable->delete(array(
                'id' => $scope['id']
            ));
        } else {
            throw new StatusCode\NotFoundException('Could not find scope');
        }
    }

    /**
     * Returns all scope names which are valid for the app and the user. The 
     * scopes are a comma seperated list. All scopes which are listed in the
     * $exclude array are excluded
     *
     * @param integer $appId
     * @param integer $userId
     * @param string $scopes
     * @param array $exclude
     * @return array
     */
    public function getValidScopes($appId, $userId, $scopes, array $exclude = array())
    {
        $scopes = explode(',', $scopes);
        $scopes = $this->appScopeTable->getValidScopes($appId, $scopes, $exclude);
        $scopes = $this->userScopeTable->getValidScopes($userId, $scopes, $exclude);

        return $scopes;
    }

    protected function insertRoutes($scopeId, $routes)
    {
        if (!empty($routes) && is_array($routes)) {
            foreach ($routes as $route) {
                if ($route->getAllow()) {
                    $this->scopeRouteTable->create(array(
                        'scopeId' => $scopeId,
                        'routeId' => $route->getRouteId(),
                        'allow'   => $route->getAllow() ? 1 : 0,
                        'methods' => $route->getMethods(),
                    ));
                }
            }
        }
    }
}
