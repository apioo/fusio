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
use Fusio\Impl\Table\App as TableApp;
use Fusio\Impl\Table\Scope as TableScope;
use Fusio\Impl\Table\User as TableUser;
use Fusio\Impl\Table\User\Scope as TableUserScope;
use PSX\Data\ResultSet;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;

/**
 * User
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class User
{
    protected $scopeTable;
    protected $userTable;
    protected $appTable;
    protected $userScopeTable;

    public function __construct(TableUser $userTable, TableScope $scopeTable, TableApp $appTable, TableUserScope $userScopeTable)
    {
        $this->userTable      = $userTable;
        $this->scopeTable     = $scopeTable;
        $this->appTable       = $appTable;
        $this->userScopeTable = $userScopeTable;
    }

    public function getAll($startIndex = 0, $search = null)
    {
        $condition = new Condition();
        $condition->notEquals('status', TableUser::STATUS_DELETED);

        if (!empty($search)) {
            $condition->like('name', '%' . $search . '%');
        }

        return new ResultSet(
            $this->userTable->getCount($condition),
            $startIndex,
            16,
            $this->userTable->getAll($startIndex, 16, 'id', Sql::SORT_DESC, $condition)
        );
    }

    public function get($userId)
    {
        $user = $this->userTable->get($userId);

        if (!empty($user)) {
            $this->appTable->setRestrictedFields(['userId', 'appSecret']);

            $user['scopes'] = $this->userTable->getScopeNames($user['id']);
            $user['apps']   = $this->appTable->getByUserId($user['id']);

            return $user;
        } else {
            throw new StatusCode\NotFoundException('Could not find user');
        }
    }

    public function create($status, $name, array $scopes = null)
    {
        $password = TokenGenerator::generateUserPassword();

        $this->userTable->create(array(
            'status'   => $status,
            'name'     => $name,
            'password' => \password_hash($password, PASSWORD_DEFAULT),
            'date'     => new DateTime(),
        ));

        // add scopes
        $this->insertScopes($this->userTable->getLastInsertId(), $scopes);

        return $password;
    }

    public function update($userId, $status, $name, array $scopes = null)
    {
        $user = $this->userTable->get($userId);

        if (!empty($user)) {
            $this->userTable->update(array(
                'id'     => $user['id'],
                'status' => $status,
                'name'   => $name,
            ));

            // delete existing scopes
            $this->userScopeTable->deleteAllFromUser($user['id']);

            // add scopes
            $this->insertScopes($user['id'], $scopes);
        } else {
            throw new StatusCode\NotFoundException('Could not find user');
        }
    }

    public function delete($userId)
    {
        $user = $this->userTable->get($userId);

        if (!empty($user)) {
            $this->userTable->update(array(
                'id'     => $user['id'],
                'status' => TableUser::STATUS_DELETED,
            ));
        } else {
            throw new StatusCode\NotFoundException('Could not find user');
        }
    }

    public function changePassword($userId, $appId, $oldPassword, $newPassword, $verifyPassword)
    {
        // we can only change the password through the backend app
        if ($appId != 1) {
            throw new StatusCode\BadRequestException('Changing the password is only possible through the backend app');
        }

        // check verify password
        if ($newPassword != $verifyPassword) {
            throw new StatusCode\BadRequestException('New password does not match the verify password');
        }

        // change password
        $result = $this->userTable->changePassword($userId, $oldPassword, $newPassword);

        if ($result) {
            return true;
        } else {
            throw new StatusCode\BadRequestException('Changing password failed');
        }
    }

    protected function insertScopes($userId, $scopes)
    {
        if (!empty($scopes) && is_array($scopes)) {
            $scopes = $this->scopeTable->getByNames($scopes);

            foreach ($scopes as $scope) {
                $this->userScopeTable->create(array(
                    'userId'  => $userId,
                    'scopeId' => $scope['id'],
                ));
            }
        }
    }
}
