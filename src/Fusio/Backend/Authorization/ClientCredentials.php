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

namespace Fusio\Backend\Authorization;

use Doctrine\DBAL\Connection;
use Fusio\Backend\Table\App;
use Fusio\Backend\Table\App\Token as AppToken;
use Fusio\Backend\Table\User;
use PSX\DateTime;
use PSX\Oauth2\AccessToken;
use PSX\Oauth2\Authorization\Exception\ServerErrorException;
use PSX\Oauth2\Provider\Credentials;
use PSX\Oauth2\Provider\GrantType\ClientCredentialsAbstract;

/**
 * ClientCredentials
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
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
				       name,
				       password
			      FROM fusio_user
			     WHERE name = :name
			       AND status = :status';

        $user = $this->connection->fetchAssoc($sql, array(
            'name'   => $credentials->getClientId(),
            'status' => User::STATUS_ADMINISTRATOR,
        ));

        if (!empty($user)) {
            if (password_verify($credentials->getClientSecret(), $user['password'])) {
                $scopes = ['backend'];

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
                $expires->add(new \DateInterval('PT1H'));
                $now = new \DateTime();

                $this->connection->executeUpdate($sql, array(
                    'app_id'  => App::BACKEND,
                    'user_id' => $user['id'],
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
                throw new ServerErrorException('Invalid password');
            }
        } else {
            throw new ServerErrorException('Unknown user');
        }
    }
}
