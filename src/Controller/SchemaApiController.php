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

namespace Fusio\Impl\Controller;

use Fusio\Impl\Authorization\Oauth2Filter;
use Fusio\Impl\Context as FusioContext;
use Fusio\Impl\Request;
use Fusio\Impl\Schema\LazySchema;
use Fusio\Impl\Validate\Validator;
use PSX\Api\Documentation;
use PSX\Api\DocumentedInterface;
use PSX\Api\Resource;
use PSX\Api\Resource\MethodAbstract;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\Record;
use PSX\Data\RecordInterface;
use PSX\Data\SchemaInterface;
use PSX\Dispatch\Filter\UserAgentEnforcer;
use PSX\Http\Exception as StatusCode;
use PSX\Loader\Context;

/**
 * SchemaApiController
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SchemaApiController extends SchemaApiAbstract implements DocumentedInterface
{
    const SCHEMA_PASSTHRU = 'passthru';

    /**
     * @Inject
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @Inject
     * @var \Fusio\Engine\ProcessorInterface
     */
    protected $processor;

    /**
     * @Inject
     * @var \Fusio\Data\SchemaManager
     */
    protected $apiSchemaManager;

    /**
     * @Inject
     * @var \Fusio\Engine\LoggerInterface
     */
    protected $apiLogger;

    /**
     * @Inject
     * @var \Fusio\Engine\Schema\LoaderInterface
     */
    protected $schemaLoader;

    /**
     * @Inject
     * @var \Fusio\Engine\App\LoaderInterface
     */
    protected $appLoader;

    /**
     * @Inject
     * @var \PSX\Cache\CacheItemPoolInterface
     */
    protected $cache;

    /**
     * @Inject
     * @var \Fusio\Validate\ValidateServiceContainer
     */
    protected $validateServiceContainer;

    /**
     * @var integer
     */
    protected $appId;

    /**
     * @var \Fusio\App
     */
    protected $app;

    /**
     * @var integer
     */
    protected $logId;

    private $activeMethod;
    private $activeValidator;

    public function onLoad()
    {
        parent::onLoad();

        // load app
        $this->app = $this->appLoader->getById($this->appId);

        // log request
        $this->logId = $this->apiLogger->log(
            $this->appId,
            $this->context->get('fusio.routeId'),
            isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
            $this->request
        );
    }

    public function getPreFilter()
    {
        $isPublic = $this->getActiveMethod()->getPublic();
        $filter   = array();

        // it is required for every request to have an user agent which
        // identifies the client
        $filter[] = new UserAgentEnforcer();

        if (!$isPublic) {
            $filter[] = new Oauth2Filter($this->connection, $this->request->getMethod(), $this->context->get('fusio.routeId'), function ($accessToken) {

                $this->appId = $accessToken['appId'];

            });
        }

        return $filter;
    }

    public function getDocumentation()
    {
        $doc    = new Documentation\Version();
        $config = $this->context->get('fusio.config');

        foreach ($config as $version) {
            $resource = new Resource($version->getStatus(), $this->context->get(Context::KEY_PATH));
            $methods  = $version->getMethods();

            foreach ($methods as $method) {
                if ($method->getActive()) {
                    $resourceMethod = Resource\Factory::getMethod($method->getName());

                    if ($method->getRequest() > 0) {
                        $resourceMethod->setRequest(new LazySchema($this->schemaLoader, $method->getRequest()));
                    }

                    if ($method->getResponse() > 0) {
                        $resourceMethod->addResponse(200, new LazySchema($this->schemaLoader, $method->getResponse()));
                    }

                    $resource->addMethod($resourceMethod);
                }
            }

            $doc->addResource($version->getName(), $resource);
        }

        return $doc;
    }

    protected function doGet(Version $version)
    {
        return $this->executeAction(new Record(), $version);
    }

    protected function doCreate(RecordInterface $record, Version $version)
    {
        return $this->executeAction($record, $version);
    }

    protected function doUpdate(RecordInterface $record, Version $version)
    {
        return $this->executeAction($record, $version);
    }

    protected function doDelete(RecordInterface $record, Version $version)
    {
        return $this->executeAction($record, $version);
    }

    protected function parseRequest(MethodAbstract $method)
    {
        if ($method->hasRequest()) {
            if ($method->getRequest()->getDefinition()->getName() == self::SCHEMA_PASSTHRU) {
                return $this->import(new Record());
            } else {
                $request = $method->getRequest();

                // if we have a schema which comes from the database set the
                // validator
                if ($request instanceof LazySchema) {
                    $this->activeValidator = new Validator(
                        $this->connection,
                        $request->getSchemaId(),
                        $this->validateServiceContainer,
                        $this->cache
                    );

                    // if we have a validator we check for parameter and
                    // fragment validation rules
                    foreach ($this->uriFragments as $name => $value) {
                        $this->activeValidator->validateProperty('/uri/fragment/' . $name, $value);
                    }

                    $parameters = $this->getParameters();
                    foreach ($parameters as $name => $value) {
                        $this->activeValidator->validateProperty('/uri/parameter/' . $name, $value);
                    }
                }

                return $this->import($request);
            }
        } else {
            return new Record();
        }
    }

    protected function getImportValidator()
    {
        return $this->activeValidator;
    }

    protected function sendResponse(MethodAbstract $method, $response)
    {
        $statusCode = $this->response->getStatusCode();
        if (!empty($statusCode) && $method->hasResponse($statusCode)) {
            $schema = $method->getResponse($statusCode);
        } else {
            $schema = $this->getSuccessfulResponse($method, $statusCode);
        }

        if ($schema instanceof SchemaInterface) {
            $this->setResponseCode($statusCode);

            if ($schema->getDefinition()->getName() == self::SCHEMA_PASSTHRU) {
                $this->setBody($response);
            } else {
                $this->setBody($this->schemaAssimilator->assimilate($schema, $response));
            }
        } else {
            $this->setResponseCode(204);
            $this->setBody('');
        }
    }

    private function executeAction(RecordInterface $record, Version $version)
    {
        $actionId = $this->getActiveMethod()->getAction();

        if ($actionId > 0) {
            try {
                $context    = new FusioContext($this->context->get('fusio.routeId'), $this->app);
                $request    = new Request($this->request, $this->uriFragments, $this->getParameters(), $record);
                $response   = $this->processor->execute($actionId, $request, $context);
                $statusCode = $response->getStatusCode();
                $headers    = $response->getHeaders();

                if (!empty($statusCode)) {
                    $this->setResponseCode($statusCode);
                }

                if (!empty($headers)) {
                    foreach ($headers as $name => $value) {
                        $this->setHeader($name, $value);
                    }
                }

                return $response->getBody();
            } catch (\Exception $e) {
                $this->apiLogger->appendError($this->logId, $e);

                throw $e;
            }
        } else {
            throw new StatusCode\ServiceUnavailableException('No action provided');
        }
    }

    private function getActiveMethod()
    {
        if ($this->activeMethod) {
            return $this->activeMethod;
        }

        $version = $this->getSubmittedVersionNumber();
        $methods = $this->getAvailableMethods();

        if ($version !== null) {
            if (isset($methods[$version])) {
                $method = $methods[$version];
            } else {
                throw new StatusCode\UnsupportedMediaTypeException('Provided media type does not exist');
            }
        } else {
            $method = end($methods);
        }

        if (empty($method)) {
            throw new StatusCode\MethodNotAllowedException('Given request method is not supported', $this->getAllowedMethods());
        }

        return $this->activeMethod = $method;
    }

    private function getAvailableMethods()
    {
        $config = $this->context->get('fusio.config');
        $result = array();

        foreach ($config as $version) {
            $methods = $version->getMethods();
            foreach ($methods as $method) {
                if ($method->getName() == $this->request->getMethod() && $method->getActive()) {
                    $result[$version->getName()] = $method;
                }
            }
        }

        ksort($result);

        return $result;
    }

    private function getAllowedMethods()
    {
        // @TODO return allowed request methods

        return array();
    }
}
