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

namespace Fusio\Impl\Authorization;

use Doctrine\DBAL\Connection;
use Fusio\Impl\Backend\Table\App;
use Fusio\Impl\Backend\Table\App\Scope;
use Fusio\Impl\Backend\Table\App\Token as AppToken;
use Fusio\Impl\Backend\Table\User;
use PSX\Oauth2\AccessToken;
use PSX\Oauth2\Authorization\Exception\ServerErrorException;
use PSX\Oauth2\Provider\Credentials;
use PSX\Oauth2\Provider\GrantType\AuthorizationCodeAbstract;

/**
 * AuthorizationCode
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class AuthorizationCode extends AuthorizationCodeAbstract
{
    protected $connection;
    protected $scope;
    protected $expireConfidential;

    public function __construct(Connection $connection, Scope $scope, $expireConfidential)
    {
        $this->connection         = $connection;
        $this->scope              = $scope;
        $this->expireConfidential = $expireConfidential;
    }

    protected function generate(Credentials $credentials, $code, $redirectUri, $clientId)
    {
        $sql = '    SELECT code.id,
                           code.appId,
                           code.userId,
                           code.scope,
                           code.date
                      FROM fusio_app_code code
                INNER JOIN fusio_app app
                        ON app.id = code.appId
                     WHERE app.appKey = :app_key
                       AND app.appSecret = :app_secret
                       AND app.status = :status
                       AND code.code = :code
                       AND code.redirectUri = :redirectUri';

        $code = $this->connection->fetchAssoc($sql, array(
            'app_key'     => $credentials->getClientId(),
            'app_secret'  => $credentials->getClientSecret(),
            'status'      => App::STATUS_ACTIVE,
            'code'        => $code,
            'redirectUri' => $redirectUri ?: '',
        ));

        $expires = new \DateTime();
        $expires->add(new \DateInterval($this->expireConfidential));

        if (!empty($code)) {
            // check whether the code is older then 30 minutes
            if (time() - strtotime($code['date']) > 60 * 30) {
                throw new ServerErrorException('Code is expired');
            }

            // scopes
            $scopes = $this->scope->getValidScopes($code['appId'], $code['scope'], ['backend']);

            if (empty($scopes)) {
                throw new ServerErrorException('No valid scope given');
            }

            // generate access token
            $now         = new \DateTime();
            $accessToken = TokenGenerator::generateToken();

            $this->connection->insert('fusio_app_token', [
                'appId'  => $code['appId'],
                'userId' => $code['userId'],
                'status'  => AppToken::STATUS_ACTIVE,
                'token'   => $accessToken,
                'scope'   => implode(',', $scopes),
                'ip'      => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
                'expire'  => $expires->format($this->connection->getDatabasePlatform()->getDateTimeFormatString()),
                'date'    => $now->format($this->connection->getDatabasePlatform()->getDateTimeFormatString()),
            ]);

            $token = new AccessToken();
            $token->setAccessToken($accessToken);
            $token->setTokenType('bearer');
            $token->setExpiresIn($expires->getTimestamp());
            $token->setScope(implode(',', $scopes));

            return $token;
        } else {
            throw new ServerErrorException('Unknown credentials');
        }
    }
}
