<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Authorization;

use Doctrine\DBAL\Connection;
use Fusio\Backend\Table\App;
use Fusio\Backend\Table\App\Token as AppToken;
use Fusio\Backend\Table\User;
use PSX\Oauth2\Provider\GrantType\ClientCredentialsAbstract;
use PSX\Oauth2\Provider\Credentials;
use PSX\Oauth2\AccessToken;
use PSX\Oauth2\Authorization\Exception\ServerErrorException;

/**
 * ClientCredentials
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class ClientCredentials extends ClientCredentialsAbstract
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function generate(Credentials $credentials, $scope)
    {
        $sql = 'SELECT id,
				       userId
			      FROM fusio_app
			     WHERE appKey = :app_key
			       AND appSecret = :app_secret
			       AND status = :status';

        $app = $this->connection->fetchAssoc($sql, array(
            'app_key'    => $credentials->getClientId(),
            'app_secret' => $credentials->getClientSecret(),
            'status'     => App::STATUS_ACTIVE,
        ));

        if (!empty($app)) {
            // validate scopes
            $scopes = $this->getValidScopes($app['id'], $scope);

            if (empty($scopes)) {
                throw new ServerErrorException('No valid scope given');
            }

            // generate access token
            $accessToken = hash('sha256', uniqid());

            $sql = 'INSERT INTO fusio_app_token
							SET appId = :app_id,
							    userId = :user_id,
							    status = :status,
							    token = :token,
							    scope = :scope,
							    ip = :ip,
							    expire = :expire,
							    date = :date';

            $expires = new \DateTime();
            $expires->add(new \DateInterval('PT6H'));
            $now = new \DateTime();

            $this->connection->executeUpdate($sql, array(
                'app_id'  => $app['id'],
                'user_id' => $app['userId'],
                'status'  => AppToken::STATUS_ACTIVE,
                'token'   => $accessToken,
                'scope'   => implode(',', $scopes),
                'ip'      => $_SERVER['REMOTE_ADDR'],
                'expire'  => $expires->format($this->connection->getDatabasePlatform()->getDateTimeFormatString()),
                'date'    => $now->format($this->connection->getDatabasePlatform()->getDateTimeFormatString()),
            ));

            $token = new AccessToken();
            $token->setAccessToken($accessToken);
            $token->setTokenType('bearer');
            $token->setExpiresIn($expires->getTimestamp());
            $token->setScope(implode(',', $scopes));

            return $token;
        } else {
            throw new ServerErrorException('Unknown user');
        }
    }

    protected function getValidScopes($appId, $scope)
    {
        $sql = '    SELECT name
				      FROM fusio_app_scope appScope
				INNER JOIN fusio_scope scope
				        ON scope.id = appScope.scopeId
				     WHERE appScope.appId = :app';

        $availableScopes = $this->connection->fetchAll($sql, array('app' => $appId));
        $result          = array();
        $scopes          = explode(',', $scope);

        foreach ($availableScopes as $availableScope) {
            if (in_array($availableScope['name'], $scopes)) {
                $result[] = $availableScope['name'];
            }
        }

        return $result;
    }
}
