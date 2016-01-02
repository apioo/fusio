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

namespace Fusio\Impl\Service\App;

use Fusio\Impl\Table\App as TableApp;
use Fusio\Impl\Table\Scope as TableScope;
use Fusio\Impl\Table\User\Scope as TableUserScope;
use Fusio\Impl\Service\App as ServiceApp;
use PSX\Data\ResultSet;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;

/**
 * Developer
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Developer
{
    protected $appService;
    protected $appTable;
    protected $userScopeTable;
    protected $appCount;
    protected $appApproval;

    public function __construct(ServiceApp $appService, TableApp $appTable, TableScope $scopeTable, TableUserScope $userScopeTable, $appCount, $appApproval)
    {
        $this->appService     = $appService;
        $this->appTable       = $appTable;
        $this->scopeTable     = $scopeTable;
        $this->userScopeTable = $userScopeTable;
        $this->appCount       = $appCount;
        $this->appApproval    = $appApproval;
    }

    public function getAll($userId, $startIndex = 0, $search = null)
    {
        $condition = new Condition();
        $condition->equals('userId', $userId);
        $condition->equals('status', TableApp::STATUS_ACTIVE);
        if (!empty($search)) {
            $condition->like('name', '%' . $search . '%');
        }

        $this->appTable->setRestrictedFields(['url', 'appSecret']);

        return new ResultSet(
            $this->appTable->getCount($condition),
            $startIndex,
            16,
            $this->appTable->getAll($startIndex, 16, 'id', Sql::SORT_DESC, $condition)
        );
    }

    public function get($userId, $appId)
    {
        $app = $this->appTable->get($appId);

        if (!empty($app)) {
            if ($app['userId'] != $userId) {
                throw new StatusCode\BadRequestException('App does not belong to the user');
            }

            return $this->appService->get($appId);
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
        }
    }

    public function create($userId, $name, $url, array $scopes = null)
    {
        // check limit of apps which an user can create
        $condition = new Condition();
        $condition->equals('userId', $userId);
        $condition->in('status', [TableApp::STATUS_ACTIVE, TableApp::STATUS_PENDING, TableApp::STATUS_DEACTIVATED]);

        if ($this->appTable->getCount($condition) > $this->appCount) {
            throw new StatusCode\BadRequestException('Maximal amount of apps reached. Please delete another app in order to register a new one');
        }

        $scopes = $this->getValidUserScopes($userId, $scopes);
        if (empty($scopes)) {
            throw new StatusCode\BadRequestException('Provide at least one valid scope for the app');
        }

        $this->appService->create(
            $userId, 
            $this->appApproval === false ? TableApp::STATUS_ACTIVE : TableApp::STATUS_PENDING,
            $name, 
            $url, 
            $scopes
        );
    }

    public function update($userId, $appId, $name, $url, array $scopes = null)
    {
        $app = $this->appTable->get($appId);

        if (!empty($app)) {
            if ($app['userId'] != $userId) {
                throw new StatusCode\BadRequestException('App does not belong to the user');
            }

            $scopes = $this->getValidUserScopes($userId, $scopes);
            if (empty($scopes)) {
                throw new StatusCode\BadRequestException('Provide at least one valid scope for the app');
            }

            $this->appService->update(
                $appId,
                $app['status'],
                $name,
                $url,
                $scopes
            );
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
        }
    }

    public function delete($userId, $appId)
    {
        $app = $this->appTable->get($appId);

        if (!empty($app)) {
            if ($app['userId'] != $userId) {
                throw new StatusCode\BadRequestException('App does not belong to the user');
            }

            $this->appService->delete($appId);
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
        }
    }

    protected function getValidUserScopes($userId, $scopes)
    {
        if (empty($scopes)) {
            return [];
        }

        $userScopes = $this->userScopeTable->getByUserId($userId);
        $scopes     = $this->scopeTable->getByNames($scopes);

        // check that the user can assign only the scopes which are also
        // assigned to the user account
        $scopes = array_filter($scopes, function ($scope) use ($userScopes) {

            foreach ($userScopes as $userScope) {
                if ($userScope['scopeId'] == $scope['id']) {
                    return true;
                }
            }

            return false;

        });

        return array_map(function($scope){
            return $scope['name'];
        }, $scopes);
    }
}
