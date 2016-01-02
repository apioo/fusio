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

use Closure;
use Doctrine\DBAL\Connection;
use Fusio\Impl\Table\App\Token as AppToken;
use PSX\Dispatch\Filter\Oauth2Authentication;
use PSX\Oauth2\Authorization\Exception\InvalidScopeException;

/**
 * Oauth2Filter
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Oauth2Filter extends Oauth2Authentication
{
    protected $connection;
    protected $requestMethod;
    protected $routeId;
    protected $appCallback;

    public function __construct(Connection $connection, $requestMethod, $routeId, Closure $appCallback)
    {
        parent::__construct(function ($token) {
            return $this->isValidToken($token);
        });

        $this->connection    = $connection;
        $this->requestMethod = $requestMethod;
        $this->routeId       = $routeId;
        $this->appCallback   = $appCallback;
    }

    protected function isValidToken($token)
    {
        $now = new \DateTime();
        $sql = 'SELECT appToken.appId,
				       appToken.userId,
				       appToken.token,
				       appToken.scope,
				       appToken.expire,
				       appToken.date
				  FROM fusio_app_token appToken
				 WHERE appToken.token = :token
				   AND appToken.status = :status
				   AND (appToken.expire IS NULL OR appToken.expire > :now)';

        $accessToken = $this->connection->fetchAssoc($sql, array(
            'token'  => $token,
            'status' => AppToken::STATUS_ACTIVE,
            'now'    => $now->format($this->connection->getDatabasePlatform()->getDateTimeFormatString()),
        ));

        if (!empty($accessToken)) {
            // these are the scopes which are assigned to the token
            $entitledScopes = explode(',', $accessToken['scope']);

            // get all scopes which are assigned to this route
            $sql = '    SELECT scope.name,
					           scopeRoutes.allow,
					           scopeRoutes.methods
					      FROM fusio_scope_routes scopeRoutes
					INNER JOIN fusio_scope scope
					        ON scope.id = scopeRoutes.scopeId
					     WHERE scopeRoutes.routeId = :route';

            $availableScopes = $this->connection->fetchAll($sql, array('route' => $this->routeId));

            // now we check whether the assigned scopes are allowed to
            // access this route. We must have at least one scope which
            // explicit allows the request
            $isAllowed = false;

            foreach ($entitledScopes as $entitledScope) {
                foreach ($availableScopes as $scope) {
                    if ($scope['name'] == $entitledScope && $scope['allow'] == 1 && in_array($this->requestMethod, explode('|', $scope['methods']))) {
                        $isAllowed = true;
                        break 2;
                    }
                }
            }

            if ($isAllowed) {
                call_user_func($this->appCallback, $accessToken);

                return true;
            } else {
                throw new InvalidScopeException('Access to this resource is not in the scope of the provided token', 400);
            }
        }

        return false;
    }
}
