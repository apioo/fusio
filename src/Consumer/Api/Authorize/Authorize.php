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

namespace Fusio\Impl\Consumer\Api\Authorize;

use DateTime;
use Fusio\Impl\Authorization\ProtectionTrait;
use Fusio\Impl\Table\App;
use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Filter as PSXFilter;
use PSX\Http\Exception as StatusCode;
use PSX\Loader\Context;
use PSX\OpenSsl;
use PSX\Sql;
use PSX\Sql\Condition;
use PSX\Uri;
use PSX\Url;
use PSX\Validate;

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
     * @Inject
     * @var \Fusio\Impl\Service\Scope
     */
    protected $scopeService;

    /**
     * @Inject
     * @var \Fusio\Impl\Service\App
     */
    protected $appService;

    /**
     * @Inject
     * @var \Fusio\Impl\Service\App\Code
     */
    protected $appCodeService;

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
    protected function doPost(RecordInterface $record, Version $version)
    {
        $responseType = $record->getResponseType();
        $clientId     = $record->getClientId();
        $redirectUri  = $record->getRedirectUri();
        $scope        = $record->getScope();
        $state        = $record->getState();

        // response type
        if (!in_array($responseType, ['code', 'token'])) {
            throw new StatusCode\BadRequestException('Invalid response type');
        }

        // client id
        $app = $this->appService->getByAppKey($clientId);
        if (empty($app)) {
            throw new StatusCode\BadRequestException('Unknown client id');
        }

        // redirect uri
        if (!empty($redirectUri)) {
            $redirectUri = new Uri($redirectUri);

            if (!$redirectUri->isAbsolute()) {
                throw new StatusCode\BadRequestException('Redirect uri must be an absolute url');
            }

            if (!in_array($redirectUri->getScheme(), ['http', 'https'])) {
                throw new StatusCode\BadRequestException('Invalid redirect uri scheme');
            }

            $url = $app['url'];
            if (!empty($url)) {
                $url = new Url($url);
                if ($url->getHost() != $redirectUri->getHost()) {
                    throw new StatusCode\BadRequestException('Redirect uri must have the same host as the app url');
                }
            } else {
                throw new StatusCode\BadRequestException('App has no url configured');
            }
        } else {
            $redirectUri = null;
        }

        // scopes
        $scopes = $this->scopeService->getValidScopes($app['id'], $this->userId, $scope, ['backend']);
        if (empty($scopes)) {
            throw new StatusCode\BadRequestException('No valid scopes provided');
        }

        // save the decision of the user. We save the decision so that it is 
        // possible for the user to revoke the access later on
        $this->saveUserDecision($app['id'], $record->getAllow());

        if ($record->getAllow()) {
            if ($responseType == 'token') {
                // check whether implicit grant is allowed
                if ($this->config['fusio_grant_implicit'] !== true) {
                    throw new StatusCode\BadRequestException('Token response type is not supported');
                }

                // redirect uri is required for token types
                if (!$redirectUri instanceof Uri) {
                    throw new StatusCode\BadRequestException('Redirect uri is required');
                }

                // generate access token
                $accessToken = $this->appService->generateAccessToken(
                    $app['id'], 
                    $this->userId, 
                    $scopes, 
                    isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1', 
                    new \DateInterval($this->config->get('fusio_expire_implicit'))
                );

                $parameters = $accessToken->getRecordInfo()->getData();

                if (!empty($state)) {
                    $parameters['state'] = $state;
                }

                $redirectUri = $redirectUri->withFragment(http_build_query($parameters, '', '&'));

                return [
                    'type' => 'token',
                    'token' => $accessToken,
                    'redirectUri' => $redirectUri,
                ];
            } else {
                // generate code which can be later exchanged by the app with an
                // access token
                $code = $this->appCodeService->generateCode(
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
                    'type' => 'code',
                    'code' => $code,
                    'redirectUri' => $redirectUri,
                ];
            }
        } else {
            // @TODO delete all previously issued tokens for this app?

            if ($redirectUri instanceof Uri) {
                $parameters = array();
                $parameters['error'] = 'access_denied';

                if (!empty($state)) {
                    $parameters['state'] = $state;
                }

                if ($responseType == 'token') {
                    $redirectUri = $redirectUri->withFragment(http_build_query($parameters, '', '&'));
                } else {
                    $redirectUri = $redirectUri->withParameters($parameters);
                }
            } else {
                $redirectUri = '#';
            }

            return [
                'type' => 'access_denied',
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
    protected function doPut(RecordInterface $record, Version $version)
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

    protected function saveUserDecision($appId, $allow)
    {
        $condition = new Condition();
        $condition->equals('userId', $this->userId);
        $condition->equals('appId', $appId);

        $table   = $this->tableManager->getTable('Fusio\Impl\Table\User\Grant');
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
