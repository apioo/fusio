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

use Fusio\Impl\Authorization\TokenGenerator;
use Fusio\Impl\Backend\Table\App as TableApp;
use Fusio\Impl\Backend\Table\App\Scope as TableAppScope;
use Fusio\Impl\Backend\Table\App\Token as TableAppToken;
use Fusio\Impl\Backend\Table\Scope as TableScope;
use Fusio\Impl\Backend\Table\User\Scope as TableUserScope;
use PSX\Data\ResultSet;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;

/**
 * App
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class App
{
    protected $appTable;
    protected $scopeTable;
    protected $appScopeTable;
    protected $userScopeTable;
    protected $appTokenTable;

    public function __construct(TableApp $appTable, TableScope $scopeTable, TableAppScope $appScopeTable, TableUserScope $userScopeTable, TableAppToken $appTokenTable)
    {
        $this->appTable       = $appTable;
        $this->scopeTable     = $scopeTable;
        $this->appScopeTable  = $appScopeTable;
        $this->userScopeTable = $userScopeTable;
        $this->appTokenTable  = $appTokenTable;
    }

    public function getAll($startIndex = 0, $search = null)
    {
        $condition = new Condition();
        $condition->in('status', [TableApp::STATUS_ACTIVE, TableApp::STATUS_PENDING]);

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

    public function get($appId)
    {
        $app = $this->appTable->get($appId);

        if (!empty($app)) {
            if ($app['status'] == TableApp::STATUS_DELETED) {
                throw new StatusCode\GoneException('App was deleted');
            }

            $app['scopes'] = $this->scopeTable->getByApp($app['id']);
            $app['tokens'] = $this->appTokenTable->getTokensByApp($app['id']);

            return $app;
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
        }
    }

    public function getPublic($appKey, $scope)
    {
        $condition = new Condition();
        $condition->equals('appKey', $appKey);
        $condition->equals('status', TableApp::STATUS_ACTIVE);

        $this->appTable->setRestrictedFields(['userId', 'status', 'appKey', 'appSecret', 'date']);

        $app = $this->appTable->getOneBy($condition);

        if (!empty($app)) {
            $app['scopes'] = $this->appScopeTable->getByApp($app['id'], $scope, ['backend']);

            return $app;
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
        }
    }

    public function create($userId, $status, $name, $url, array $scopes = null)
    {
        $appKey    = TokenGenerator::generateAppKey();
        $appSecret = TokenGenerator::generateAppSecret();

        $this->appTable->create(array(
            'userId'    => $userId,
            'status'    => $status,
            'name'      => $name,
            'url'       => $url,
            'appKey'    => $appKey,
            'appSecret' => $appSecret,
            'date'      => new DateTime(),
        ));

        $appId = $this->appTable->getLastInsertId();

        // insert scopes
        $this->insertScopes($appId, $scopes);
    }

    public function update($appId, $status, $name, $url, array $scopes = null)
    {
        $app = $this->appTable->get($appId);

        if (!empty($app)) {
            if ($app['status'] == TableApp::STATUS_DELETED) {
                throw new StatusCode\GoneException('App was deleted');
            }

            $this->appTable->update(array(
                'id'     => $app->getId(),
                'status' => $status,
                'name'   => $name,
                'url'    => $url,
            ));

            // delete existing scopes
            $this->appScopeTable->deleteAllFromApp($appId);

            // insert scopes
            $this->insertScopes($appId, $scopes);
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
        }
    }

    public function delete($appId)
    {
        $app = $this->appTable->get($appId);

        if (!empty($app)) {
            if ($app['status'] == TableApp::STATUS_DELETED) {
                throw new StatusCode\GoneException('App was deleted');
            }

            $this->appTable->update(array(
                'id'     => $app['id'],
                'status' => TableApp::STATUS_DELETED,
            ));
        } else {
            throw new StatusCode\NotFoundException('Could not find app');
        }
    }

    public function removeToken($appId, $tokenId)
    {
        $app = $this->appTable->get($appId);

        if (!empty($app)) {
            $this->appTokenTable->removeTokenFromApp($appId, $tokenId);
        } else {
            throw new StatusCode\NotFoundException('Invalid app');
        }
    }

    public function insertScopes($appId, $scopes)
    {
        if (!empty($scopes) && is_array($scopes)) {
            $scopes = $this->scopeTable->getByNames($scopes);

            foreach ($scopes as $scope) {
                $this->appScopeTable->create(array(
                    'appId'   => $appId,
                    'scopeId' => $scope['id'],
                ));
            }
        }
    }
}
