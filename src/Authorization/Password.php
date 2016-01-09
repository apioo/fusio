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

namespace Fusio\Impl\Authorization;

use Doctrine\DBAL\Connection;
use Fusio\Impl\Service\App as AppService;
use Fusio\Impl\Service\User as UserService;
use Fusio\Impl\Service\Scope as ScopeService;
use Fusio\Impl\Table\App;
use Fusio\Impl\Table\App\Scope;
use Fusio\Impl\Table\App\Token as AppToken;
use Fusio\Impl\Table\User;
use PSX\Oauth2\AccessToken;
use PSX\Oauth2\Authorization\Exception\ServerErrorException;
use PSX\Oauth2\Provider\Credentials;
use PSX\Oauth2\Provider\GrantType\PasswordAbstract;

/**
 * PasswordCredentials
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Password extends PasswordAbstract
{
    protected $appService;
    protected $scopeService;
    protected $userService;
    protected $expireApp;

    public function __construct(AppService $appService, ScopeService $scopeService, UserService $userService, $expireApp)
    {
        $this->appService   = $appService;
        $this->scopeService = $scopeService;
        $this->userService  = $userService;
        $this->expireApp    = $expireApp;
    }

    protected function generate(Credentials $credentials, $username, $password, $scope)
    {
        $app = $this->appService->getByAppKeyAndSecret(
            $credentials->getClientId(),
            $credentials->getClientSecret()
        );

        if (!empty($app)) {
            // check user
            $userId = $this->userService->authenticateUser(
                $username, 
                $password, 
                [User::STATUS_ADMINISTRATOR, User::STATUS_CONSUMER]
            );

            if (!empty($userId)) {
                // validate scopes
                $scopes = $this->scopeService->getValidScopes($app['id'], $userId, $scope, ['backend']);
                if (empty($scopes)) {
                    throw new ServerErrorException('No valid scope given');
                }

                // generate access token
                return $this->appService->generateAccessToken(
                    $app['id'], 
                    $userId, 
                    $scopes, 
                    isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
                    new \DateInterval($this->expireApp)
                );
            } else {
                throw new ServerErrorException('Unknown user');
            }
        } else {
            throw new ServerErrorException('Unknown credentials');
        }
    }
}
