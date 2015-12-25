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

namespace Fusio\Impl\Consumer\Api\Authorize;

use DateTime;
use Fusio\Impl\Authorization\ProtectionTrait;
use Fusio\Impl\Authorization\TokenGenerator;
use Fusio\Impl\Backend\Table\App;
use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Filter as PSXFilter;
use PSX\Loader\Context;
use PSX\OpenSsl;
use PSX\Sql;
use PSX\Sql\Condition;
use PSX\Uri;
use PSX\Url;
use PSX\Util\Uuid;
use PSX\Util\CurveArray;
use PSX\Validate;
use RuntimeException;

/**
 * Authorize
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Authorize extends SchemaApiAbstract
{
    use ProtectionTrait;

    /**
     * @Inject
     * @var \PSX\Data\Schema\SchemaManagerInterface
     */
    protected $schemaManager;

    /**
     * @Inject
     * @var \PSX\Sql\TableManager
     */
    protected $tableManager;

    /**
     * @return \PSX\Api\DocumentationInterface
     */
    public function getDocumentation()
    {
        $resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));

        $resource->addMethod(Resource\Factory::getMethod('POST')
            ->setRequest($this->schemaManager->getSchema('Fusio\Impl\Consumer\Schema\Authorize\Request'))
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Impl\Consumer\Schema\Authorize\Response'))
        );

        return new Documentation\Simple($resource);
    }

    /**
     * Returns the GET response
     *
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doGet(Version $version)
    {
    }

    /**
     * Returns the POST response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doCreate(RecordInterface $record, Version $version)
    {
        $responseType = $record->getResponseType();
        $clientId     = $record->getClientId();
        $redirectUri  = $record->getRedirectUri();
        $scope        = $record->getScope();
        $state        = $record->getState();

        // response type
        if ($responseType != 'code') {
            throw new RuntimeException('Invalid response type');
        }

        // client id
        $sql = 'SELECT id, 
                       name, 
                       url 
                  FROM fusio_app 
                 WHERE appKey = :appKey
                   AND status = :status';

        $app = $this->connection->fetchAssoc($sql, [
            'appKey' => $clientId,
            'status' => App::STATUS_ACTIVE
        ]);

        if (empty($app)) {
            throw new RuntimeException('Unknown client id');
        }

        // redirect uri
        if (!empty($redirectUri)) {
            $redirectUri = new Uri($redirectUri);

            if (!$redirectUri->isAbsolute()) {
                throw new RuntimeException('Redirect uri must be an absolute url');
            }

            if (!in_array($redirectUri->getScheme(), ['http', 'https'])) {
                throw new RuntimeException('Invalid redirect uri scheme');
            }

            $url = $app['url'];
            if (!empty($url)) {
                $url = new Url($url);
                if ($url->getHost() != $redirectUri->getHost()) {
                    throw new RuntimeException('Redirect uri must have the same host as the app url');
                }
            } else {
                throw new RuntimeException('App has no url configured');
            }
        } else {
            $redirectUri = null;
        }

        // scopes
        $scopes = $this->tableManager->getTable('Fusio\Impl\Backend\Table\App\Scope')
            ->getValidScopes($app['id'], $scope, ['backend']);

        if (empty($scopes)) {
            throw new RuntimeException('No valid scopes provided');
        }

        // save the decision of the user
        $this->saveUserDecision($app['id'], $record->getAllow());

        if ($record->getAllow()) {
            // @TODO if we are authenticated and the user has already approved the 
            // app we can directly generate a code

            // generate code
            $code = $this->tableManager->getTable('Fusio\Impl\Backend\Table\App\Code')->generateCode(
                $app['id'],
                $this->userId,
                $redirectUri,
                $scopes
            );

            if ($redirectUri instanceof Uri) {
                $parameters = array();
                $parameters['code'] = $code;
                $parameters['state'] = $state;

                $redirectUri = $redirectUri->withParameters($parameters);
            } else {
                $redirectUri = '#';
            }

            return [
                'code' => $code,
                'redirectUri' => $redirectUri
            ];
        } else {
            // @TODO delete all previously issued tokens for this app?

            if ($redirectUri instanceof Uri) {
                $parameters = $redirectUri->getParameters();
                $parameters['error'] = 'access_denied';

                $redirectUri = $redirectUri->withParameters($parameters);
            } else {
                $redirectUri = '#';
            }

            return [
                'redirectUri' => $redirectUri
            ];
        }
    }

    /**
     * Returns the PUT response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doUpdate(RecordInterface $record, Version $version)
    {
    }

    /**
     * Returns the DELETE response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doDelete(RecordInterface $record, Version $version)
    {
    }

    protected function getValidScopes($appId, $scope)
    {
        $sql = '    SELECT name
                      FROM fusio_app_scope appScope
                INNER JOIN fusio_scope scope
                        ON scope.id = appScope.scopeId
                     WHERE appScope.appId = :appId';

        $availableScopes = $this->connection->fetchAll($sql, array('appId' => $appId));
        $result          = array();
        $scopes          = explode(',', $scope);

        foreach ($availableScopes as $availableScope) {
            if (in_array($availableScope['name'], $scopes)) {
                $result[] = $availableScope;
            }
        }

        return $result;
    }

    protected function saveUserDecision($appId, $allow)
    {
        $condition = new Condition();
        $condition->equals('userId', $this->userId);
        $condition->equals('appId', $appId);

        $table   = $this->tableManager->getTable('Fusio\Impl\Backend\Table\User\Grant');
        $userApp = $table->getOneBy($condition);

        if (empty($userApp)) {
            $table->create([
                'userId' => $this->userId,
                'appId'  => $appId,
                'allow'  => $allow ? 1 : 0,
                'date'   => new \DateTime(),
            ]);
        } else {
            $table->update([
                'id'     => $userApp['id'],
                'userId' => $this->userId,
                'appId'  => $appId,
                'allow'  => $allow ? 1 : 0,
                'date'   => new \DateTime(),
            ]);
        }
    }
}
