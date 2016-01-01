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

namespace Fusio\Impl\Database\Version;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Fusio\Impl\Authorization\TokenGenerator;
use Fusio\Impl\Database\VersionInterface;
use Fusio\Impl\Schema\Parser;
use PSX\Data\Record;
use PSX\Data\Schema\Property;
use PSX\OpenSsl;

/**
 * Version010
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Version010 implements VersionInterface
{
    public function getSchema()
    {
        $schema = new Schema();

        $actionTable = $schema->createTable('fusio_action');
        $actionTable->addColumn('id', 'integer', array('autoincrement' => true));
        $actionTable->addColumn('status', 'integer');
        $actionTable->addColumn('name', 'string', array('length' => 64));
        $actionTable->addColumn('class', 'string', array('length' => 255));
        $actionTable->addColumn('config', 'blob', array('notnull' => false));
        $actionTable->addColumn('date', 'datetime');
        $actionTable->setPrimaryKey(array('id'));
        $actionTable->addUniqueIndex(array('name'));

        $actionClassTable = $schema->createTable('fusio_action_class');
        $actionClassTable->addColumn('id', 'integer', array('autoincrement' => true));
        $actionClassTable->addColumn('class', 'string', array('length' => 255));
        $actionClassTable->setPrimaryKey(array('id'));
        $actionClassTable->addUniqueIndex(array('class'));

        $appTable = $schema->createTable('fusio_app');
        $appTable->addColumn('id', 'integer', array('autoincrement' => true));
        $appTable->addColumn('userId', 'integer');
        $appTable->addColumn('status', 'integer');
        $appTable->addColumn('name', 'string', array('length' => 64));
        $appTable->addColumn('url', 'string', array('length' => 255));
        $appTable->addColumn('appKey', 'string', array('length' => 255));
        $appTable->addColumn('appSecret', 'string', array('length' => 255));
        $appTable->addColumn('date', 'datetime');
        $appTable->setPrimaryKey(array('id'));
        $appTable->addUniqueIndex(array('appKey'));

        $appScopeTable = $schema->createTable('fusio_app_scope');
        $appScopeTable->addColumn('id', 'integer', array('autoincrement' => true));
        $appScopeTable->addColumn('appId', 'integer');
        $appScopeTable->addColumn('scopeId', 'integer');
        $appScopeTable->setPrimaryKey(array('id'));

        $appTokenTable = $schema->createTable('fusio_app_token');
        $appTokenTable->addColumn('id', 'integer', array('autoincrement' => true));
        $appTokenTable->addColumn('appId', 'integer');
        $appTokenTable->addColumn('userId', 'integer');
        $appTokenTable->addColumn('status', 'integer', array('default' => 1));
        $appTokenTable->addColumn('token', 'string', array('length' => 255));
        $appTokenTable->addColumn('scope', 'string', array('length' => 255));
        $appTokenTable->addColumn('ip', 'string', array('length' => 40));
        $appTokenTable->addColumn('expire', 'datetime', array('notnull' => false));
        $appTokenTable->addColumn('date', 'datetime');
        $appTokenTable->setPrimaryKey(array('id'));
        $appTokenTable->addUniqueIndex(array('token'));

        $appCodeTable = $schema->createTable('fusio_app_code');
        $appCodeTable->addColumn('id', 'integer', array('autoincrement' => true));
        $appCodeTable->addColumn('appId', 'integer');
        $appCodeTable->addColumn('userId', 'integer');
        $appCodeTable->addColumn('code', 'string', array('length' => 255));
        $appCodeTable->addColumn('redirectUri', 'string', array('length' => 255));
        $appCodeTable->addColumn('scope', 'string', array('length' => 255));
        $appCodeTable->addColumn('date', 'datetime');
        $appCodeTable->setPrimaryKey(array('id'));
        $appCodeTable->addUniqueIndex(array('code'));

        $connectionTable = $schema->createTable('fusio_connection');
        $connectionTable->addColumn('id', 'integer', array('autoincrement' => true));
        $connectionTable->addColumn('name', 'string', array('length' => 64));
        $connectionTable->addColumn('class', 'string', array('length' => 255));
        $connectionTable->addColumn('config', 'blob', array('notnull' => false));
        $connectionTable->setPrimaryKey(array('id'));
        $connectionTable->addUniqueIndex(array('name'));

        $connectionClassTable = $schema->createTable('fusio_connection_class');
        $connectionClassTable->addColumn('id', 'integer', array('autoincrement' => true));
        $connectionClassTable->addColumn('class', 'string', array('length' => 255));
        $connectionClassTable->setPrimaryKey(array('id'));
        $connectionClassTable->addUniqueIndex(array('class'));

        $logTable = $schema->createTable('fusio_log');
        $logTable->addColumn('id', 'integer', array('autoincrement' => true));
        $logTable->addColumn('appId', 'integer', array('notnull' => false));
        $logTable->addColumn('routeId', 'integer', array('notnull' => false));
        $logTable->addColumn('ip', 'string', array('length' => 40));
        $logTable->addColumn('userAgent', 'string', array('length' => 255));
        $logTable->addColumn('method', 'string', array('length' => 16));
        $logTable->addColumn('path', 'string', array('length' => 255));
        $logTable->addColumn('header', 'text');
        $logTable->addColumn('body', 'text', array('notnull' => false));
        $logTable->addColumn('date', 'datetime');
        $logTable->setPrimaryKey(array('id'));

        $logErrorTable = $schema->createTable('fusio_log_error');
        $logErrorTable->addColumn('id', 'integer', array('autoincrement' => true));
        $logErrorTable->addColumn('logId', 'integer');
        $logErrorTable->addColumn('message', 'string', array('length' => 500));
        $logErrorTable->addColumn('trace', 'text');
        $logErrorTable->addColumn('file', 'string', array('length' => 255));
        $logErrorTable->addColumn('line', 'integer');
        $logErrorTable->setPrimaryKey(array('id'));

        $routesTable = $schema->createTable('fusio_routes');
        $routesTable->addColumn('id', 'integer', array('autoincrement' => true));
        $routesTable->addColumn('status', 'integer', array('default' => 1));
        $routesTable->addColumn('methods', 'string', array('length' => 64));
        $routesTable->addColumn('path', 'string', array('length' => 255));
        $routesTable->addColumn('controller', 'string', array('length' => 255));
        $routesTable->addColumn('config', 'blob', array('notnull' => false));
        $routesTable->setPrimaryKey(array('id'));
        $routesTable->addUniqueIndex(array('path'));

        $routesActionTable = $schema->createTable('fusio_routes_action');
        $routesActionTable->addColumn('id', 'integer', array('autoincrement' => true));
        $routesActionTable->addColumn('routeId', 'integer');
        $routesActionTable->addColumn('actionId', 'integer');
        $routesActionTable->addColumn('status', 'integer');
        $routesActionTable->setPrimaryKey(array('id'));
        $routesActionTable->addUniqueIndex(array('routeId', 'actionId'));

        $routesSchemaTable = $schema->createTable('fusio_routes_schema');
        $routesSchemaTable->addColumn('id', 'integer', array('autoincrement' => true));
        $routesSchemaTable->addColumn('routeId', 'integer');
        $routesSchemaTable->addColumn('schemaId', 'integer');
        $routesSchemaTable->addColumn('status', 'integer');
        $routesSchemaTable->setPrimaryKey(array('id'));
        $routesSchemaTable->addUniqueIndex(array('routeId', 'schemaId'));

        $schemaTable = $schema->createTable('fusio_schema');
        $schemaTable->addColumn('id', 'integer', array('autoincrement' => true));
        $schemaTable->addColumn('status', 'integer');
        $schemaTable->addColumn('name', 'string', array('length' => 64));
        $schemaTable->addColumn('propertyName', 'string', array('length' => 64, 'notnull' => false));
        $schemaTable->addColumn('source', 'text');
        $schemaTable->addColumn('cache', 'blob');
        $schemaTable->setPrimaryKey(array('id'));
        $schemaTable->addUniqueIndex(array('name'));

        $scopeTable = $schema->createTable('fusio_scope');
        $scopeTable->addColumn('id', 'integer', array('autoincrement' => true));
        $scopeTable->addColumn('name', 'string', array('length' => 32));
        $scopeTable->addColumn('description', 'string', array('length' => 255));
        $scopeTable->setPrimaryKey(array('id'));
        $scopeTable->addUniqueIndex(array('name'));

        $metaTable = $schema->createTable('fusio_meta');
        $metaTable->addColumn('id', 'integer', array('autoincrement' => true));
        $metaTable->addColumn('version', 'string', array('length' => 16));
        $metaTable->addColumn('installDate', 'datetime');
        $metaTable->setPrimaryKey(array('id'));

        $userTable = $schema->createTable('fusio_user');
        $userTable->addColumn('id', 'integer', array('autoincrement' => true));
        $userTable->addColumn('status', 'integer');
        $userTable->addColumn('name', 'string', array('length' => 64));
        $userTable->addColumn('password', 'string', array('length' => 255));
        $userTable->addColumn('date', 'datetime');
        $userTable->setPrimaryKey(array('id'));
        $userTable->addUniqueIndex(array('name'));

        $scopeRoutesTable = $schema->createTable('fusio_scope_routes');
        $scopeRoutesTable->addColumn('id', 'integer', array('autoincrement' => true));
        $scopeRoutesTable->addColumn('scopeId', 'integer');
        $scopeRoutesTable->addColumn('routeId', 'integer');
        $scopeRoutesTable->addColumn('allow', 'smallint');
        $scopeRoutesTable->addColumn('methods', 'string', array('length' => 64, 'notnull' => false));
        $scopeRoutesTable->setPrimaryKey(array('id'));

        $userGrantTable = $schema->createTable('fusio_user_grant');
        $userGrantTable->addColumn('id', 'integer', array('autoincrement' => true));
        $userGrantTable->addColumn('userId', 'integer');
        $userGrantTable->addColumn('appId', 'integer');
        $userGrantTable->addColumn('allow', 'integer');
        $userGrantTable->addColumn('date', 'datetime');
        $userGrantTable->setPrimaryKey(array('id'));
        $userGrantTable->addUniqueIndex(array('userId', 'appId'));

        $userScopeTable = $schema->createTable('fusio_user_scope');
        $userScopeTable->addColumn('id', 'integer', array('autoincrement' => true));
        $userScopeTable->addColumn('userId', 'integer');
        $userScopeTable->addColumn('scopeId', 'integer');
        $userScopeTable->setPrimaryKey(array('id'));

        $appTable->addForeignKeyConstraint($userTable, array('userId'), array('id'), array(), 'appUserId');

        $appScopeTable->addForeignKeyConstraint($appTable, array('appId'), array('id'), array(), 'appScopeAppId');
        $appScopeTable->addForeignKeyConstraint($scopeTable, array('scopeId'), array('id'), array(), 'appScopeScopeId');

        $appTokenTable->addForeignKeyConstraint($appTable, array('appId'), array('id'), array(), 'appTokenAppId');
        $appTokenTable->addForeignKeyConstraint($userTable, array('userId'), array('id'), array(), 'appTokenUserId');

        $logTable->addForeignKeyConstraint($appTable, array('appId'), array('id'), array(), 'logAppId');
        $logTable->addForeignKeyConstraint($routesTable, array('routeId'), array('id'), array(), 'logRouteId');

        $logErrorTable->addForeignKeyConstraint($logTable, array('logId'), array('id'), array(), 'logErrorLogId');

        $routesActionTable->addForeignKeyConstraint($routesTable, array('routeId'), array('id'), array(), 'routesActionRouteId');
        $routesActionTable->addForeignKeyConstraint($actionTable, array('actionId'), array('id'), array(), 'routesActionActionId');

        $routesSchemaTable->addForeignKeyConstraint($routesTable, array('routeId'), array('id'), array(), 'routesSchemaRouteId');
        $routesSchemaTable->addForeignKeyConstraint($schemaTable, array('schemaId'), array('id'), array(), 'routesSchemaSchemaId');

        $scopeRoutesTable->addForeignKeyConstraint($routesTable, array('routeId'), array('id'), array(), 'scopeRoutesRouteId');
        $scopeRoutesTable->addForeignKeyConstraint($scopeTable, array('scopeId'), array('id'), array(), 'scopeRoutesScopeId');

        $userScopeTable->addForeignKeyConstraint($scopeTable, array('scopeId'), array('id'), array(), 'userScopeScopeId');
        $userScopeTable->addForeignKeyConstraint($userTable, array('userId'), array('id'), array(), 'userScopeUserId');

        return $schema;
    }

    public function executeInstall(Connection $connection)
    {
        $inserts = $this->getInstallInserts();

        foreach ($inserts as $tableName => $queries) {
            foreach ($queries as $data) {
                $connection->insert($tableName, $data);
            }
        }
    }

    public function executeUpgrade(Connection $connection)
    {
    }

    public function getInstallInserts()
    {
        $backendAppKey     = TokenGenerator::generateAppKey();
        $backendAppSecret  = TokenGenerator::generateAppSecret();
        $consumerAppKey    = TokenGenerator::generateAppKey();
        $consumerAppSecret = TokenGenerator::generateAppSecret();
        $password          = \password_hash(TokenGenerator::generateUserPassword(), PASSWORD_DEFAULT);

        $parser    = new Parser();
        $now       = new DateTime();

        $schema    = $this->getPassthruSchema();
        $cache     = $parser->parse($schema);
        $response  = $this->getWelcomeResponse();
        $config    = $this->getWelcomeConfig();

        return [
            'fusio_user' => [
                ['status' => 1, 'name' => 'Administrator', 'password' => $password, 'date' => $now->format('Y-m-d H:i:s')],
            ],
            'fusio_app' => [
                ['userId' => 1, 'status' => 1, 'name' => 'Backend',  'url' => 'http://fusio-project.org', 'appKey' => $backendAppKey, 'appSecret' => $backendAppSecret, 'date' => $now->format('Y-m-d H:i:s')],
                ['userId' => 1, 'status' => 1, 'name' => 'Consumer', 'url' => 'http://fusio-project.org', 'appKey' => $consumerAppKey, 'appSecret' => $consumerAppSecret, 'date' => $now->format('Y-m-d H:i:s')],
            ],
            'fusio_connection' => [
                ['name' => 'Native-Connection', 'class' => 'Fusio\Impl\Connection\Native', 'config' => null]
            ],
            'fusio_connection_class' => [
                ['class' => 'Fusio\Impl\Connection\Beanstalk'],
                ['class' => 'Fusio\Impl\Connection\DBAL'],
                ['class' => 'Fusio\Impl\Connection\DBALAdvanced'],
                ['class' => 'Fusio\Impl\Connection\MongoDB'],
                ['class' => 'Fusio\Impl\Connection\Native'],
                ['class' => 'Fusio\Impl\Connection\RabbitMQ'],
            ],
            'fusio_scope' => [
                ['name' => 'backend', 'description' => 'Access to the backend API'],
                ['name' => 'consumer', 'description' => 'Consumer API endpoint'],
                ['name' => 'authorization', 'description' => 'Authorization API endpoint'],
            ],
            'fusio_action' => [
                ['status' => 1, 'name' => 'Welcome', 'class' => 'Fusio\Impl\Action\StaticResponse', 'config' => serialize(['response' => $response]), 'date' => $now->format('Y-m-d H:i:s')],
            ],
            'fusio_action_class' => [
                ['class' => 'Fusio\Impl\Action\CacheResponse'],
                ['class' => 'Fusio\Impl\Action\Composite'],
                ['class' => 'Fusio\Impl\Action\Condition'],
                ['class' => 'Fusio\Impl\Action\HttpProxy'],
                ['class' => 'Fusio\Impl\Action\HttpRequest'],
                ['class' => 'Fusio\Impl\Action\MongoDelete'],
                ['class' => 'Fusio\Impl\Action\MongoFetchAll'],
                ['class' => 'Fusio\Impl\Action\MongoFetchRow'],
                ['class' => 'Fusio\Impl\Action\MongoInsert'],
                ['class' => 'Fusio\Impl\Action\MongoUpdate'],
                ['class' => 'Fusio\Impl\Action\MqAmqp'],
                ['class' => 'Fusio\Impl\Action\MqBeanstalk'],
                ['class' => 'Fusio\Impl\Action\Pipe'],
                ['class' => 'Fusio\Impl\Action\Processor'],
                ['class' => 'Fusio\Impl\Action\SqlExecute'],
                ['class' => 'Fusio\Impl\Action\SqlFetchAll'],
                ['class' => 'Fusio\Impl\Action\SqlFetchRow'],
                ['class' => 'Fusio\Impl\Action\StaticResponse'],
                ['class' => 'Fusio\Impl\Action\Transform'],
                ['class' => 'Fusio\Impl\Action\Validator'],
            ],
            'fusio_schema' => [
                ['status' => 1, 'name' => 'Passthru', 'source' => $schema, 'cache' => $cache]
            ],
            'fusio_routes' => [
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/action',                      'controller' => 'Fusio\Impl\Backend\Api\Action\Collection',                    'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/action/list',                 'controller' => 'Fusio\Impl\Backend\Api\Action\ListActions::doIndex',          'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/action/form',                 'controller' => 'Fusio\Impl\Backend\Api\Action\ListActions::doDetail',         'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/action/:action_id',           'controller' => 'Fusio\Impl\Backend\Api\Action\Entity',                        'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/app',                         'controller' => 'Fusio\Impl\Backend\Api\App\Collection',                       'config' => null],
                ['status' => 1, 'methods' => 'DELETE',              'path' => '/backend/app/:app_id/token/:token_id', 'controller' => 'Fusio\Impl\Backend\Api\App\Token::doRemove',                  'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/app/:app_id',                 'controller' => 'Fusio\Impl\Backend\Api\App\Entity',                           'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/connection',                  'controller' => 'Fusio\Impl\Backend\Api\Connection\Collection',                'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/connection/form',             'controller' => 'Fusio\Impl\Backend\Api\Connection\ListConnections::doDetail', 'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/connection/list',             'controller' => 'Fusio\Impl\Backend\Api\Connection\ListConnections::doIndex',  'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/connection/:connection_id',   'controller' => 'Fusio\Impl\Backend\Api\Connection\Entity',                    'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/log',                         'controller' => 'Fusio\Impl\Backend\Api\Log\Collection',                       'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/log/:log_id',                 'controller' => 'Fusio\Impl\Backend\Api\Log\Entity',                           'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/routes',                      'controller' => 'Fusio\Impl\Backend\Api\Routes\Collection',                    'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/routes/:route_id',            'controller' => 'Fusio\Impl\Backend\Api\Routes\Entity',                        'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/schema',                      'controller' => 'Fusio\Impl\Backend\Api\Schema\Collection',                    'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/schema/:schema_id',           'controller' => 'Fusio\Impl\Backend\Api\Schema\Entity',                        'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/schema/preview/:schema_id',   'controller' => 'Fusio\Impl\Backend\Api\Schema\Preview',                       'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/scope',                       'controller' => 'Fusio\Impl\Backend\Api\Scope\Collection',                     'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/scope/:scope_id',             'controller' => 'Fusio\Impl\Backend\Api\Scope\Entity',                         'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/user',                        'controller' => 'Fusio\Impl\Backend\Api\User\Collection',                      'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/user/:user_id',               'controller' => 'Fusio\Impl\Backend\Api\User\Entity',                          'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/dashboard/latest_requests',   'controller' => 'Fusio\Impl\Backend\Api\Dashboard\LatestRequests',             'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/dashboard/latest_apps',       'controller' => 'Fusio\Impl\Backend\Api\Dashboard\LatestApps',                 'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/statistic/incoming_requests', 'controller' => 'Fusio\Impl\Backend\Api\Statistic\IncomingRequests',           'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/statistic/most_used_routes',  'controller' => 'Fusio\Impl\Backend\Api\Statistic\MostUsedRoutes',             'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/statistic/most_used_apps',    'controller' => 'Fusio\Impl\Backend\Api\Statistic\MostUsedApps',               'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/statistic/errors_per_route',  'controller' => 'Fusio\Impl\Backend\Api\Statistic\ErrorsPerRoute',             'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/account/change_password',     'controller' => 'Fusio\Impl\Backend\Api\Account\ChangePassword',               'config' => null],
                ['status' => 1, 'methods' => 'POST',                'path' => '/backend/import/raml',                 'controller' => 'Fusio\Impl\Backend\Api\Import\Raml',                          'config' => null],
                ['status' => 1, 'methods' => 'POST',                'path' => '/backend/import/process',              'controller' => 'Fusio\Impl\Backend\Api\Import\Process',                       'config' => null],
                ['status' => 1, 'methods' => 'GET|POST',            'path' => '/backend/token',                       'controller' => 'Fusio\Impl\Backend\Authorization\Token',                      'config' => null],

                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/consumer/app/developer',              'controller' => 'Fusio\Impl\Consumer\Api\App\Developer\Collection',            'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/consumer/app/developer/:app_id',      'controller' => 'Fusio\Impl\Consumer\Api\App\Developer\Entity',                'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/consumer/app/grant',                  'controller' => 'Fusio\Impl\Consumer\Api\App\Grant\Collection',                'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/consumer/app/grant/:grant_id',        'controller' => 'Fusio\Impl\Consumer\Api\App\Grant\Entity',                    'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/consumer/app/meta',                   'controller' => 'Fusio\Impl\Consumer\Api\App\Meta\Entity',                     'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/consumer/authorize',                  'controller' => 'Fusio\Impl\Consumer\Api\Authorize\Authorize',                 'config' => null],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/consumer/scope',                      'controller' => 'Fusio\Impl\Consumer\Api\Scope\Collection',                    'config' => null],
                ['status' => 1, 'methods' => 'GET|POST',            'path' => '/consumer/token',                      'controller' => 'Fusio\Impl\Consumer\Authorization\Token',                     'config' => null],

                ['status' => 1, 'methods' => 'POST',                'path' => '/authorization/revoke',                'controller' => 'Fusio\Impl\Authorization\Revoke',                             'config' => null],
                ['status' => 1, 'methods' => 'GET|POST',            'path' => '/authorization/token',                 'controller' => 'Fusio\Impl\Authorization\Token',                              'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/authorization/whoami',                'controller' => 'Fusio\Impl\Authorization\Whoami',                             'config' => null],

                ['status' => 1, 'methods' => 'GET',                 'path' => '/doc',                                 'controller' => 'PSX\Controller\Tool\DocumentationController::doIndex',        'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/doc/:version/*path',                  'controller' => 'PSX\Controller\Tool\DocumentationController::doDetail',       'config' => null],

                ['status' => 1, 'methods' => 'GET',                 'path' => '/export/wsdl/:version/*path',          'controller' => 'PSX\Controller\Tool\WsdlGeneratorController',                 'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/export/raml/:version/*path',          'controller' => 'PSX\Controller\Tool\RamlGeneratorController',                 'config' => null],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/export/swagger/:version/*path',       'controller' => 'PSX\Controller\Tool\SwaggerGeneratorController::doDetail',    'config' => null],

                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/',                                    'controller' => 'Fusio\Impl\Controller\SchemaApiController',                   'config' => $config],
            ],
            'fusio_app_scope' => [
                ['appId' => 1, 'scopeId' => 1],
                ['appId' => 1, 'scopeId' => 3],
                ['appId' => 2, 'scopeId' => 2],
                ['appId' => 2, 'scopeId' => 3],
            ],
            'fusio_scope_routes' => [
                ['scopeId' => 1, 'routeId' => 1,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 2,  'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 3,  'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 4,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 5,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 6,  'allow' => 1, 'methods' => 'DELETE'],
                ['scopeId' => 1, 'routeId' => 7,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 8,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 9,  'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 10, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 11, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 12, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 13, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 14, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 15, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 16, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 17, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 18, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 19, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 20, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 21, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 22, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 23, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 24, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 25, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 26, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 27, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 28, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 29, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 30, 'allow' => 1, 'methods' => 'POST'],
                ['scopeId' => 1, 'routeId' => 31, 'allow' => 1, 'methods' => 'POST'],

                ['scopeId' => 2, 'routeId' => 33, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 2, 'routeId' => 34, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 2, 'routeId' => 35, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 2, 'routeId' => 36, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 2, 'routeId' => 37, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 2, 'routeId' => 38, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 2, 'routeId' => 39, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],

                ['scopeId' => 3, 'routeId' => 41, 'allow' => 1, 'methods' => 'POST'],
                ['scopeId' => 3, 'routeId' => 43, 'allow' => 1, 'methods' => 'GET'],
            ],
            'fusio_user_scope' => [
                ['userId' => 1, 'scopeId' => 1],
                ['userId' => 1, 'scopeId' => 2],
                ['userId' => 1, 'scopeId' => 3],
            ],
        ];
    }

    protected function getPassthruSchema()
    {
        return json_encode([
            'id' => 'http://fusio-project.org',
            'title' => 'passthru',
            'type' => 'object',
            'description' => 'No schema was specified all data will pass thru. Please contact the API provider for more informations about the data format.',
            'properties' => new \stdClass(),
        ], JSON_PRETTY_PRINT);
    }

    protected function getWelcomeResponse()
    {
        return <<<'JSON'
{
    "message": "Congratulations the installation of Fusio was successful",
    "links": [{
        "rel": "about",
        "name": "http://fusio-project.org"
    }]
}
JSON;
    }

    protected function getWelcomeConfig()
    {
        $config = [Record::fromArray([
            'active' => true,
            'status' => 4,
            'name' => '1',
            'methods' => [Record::fromArray([
                'active' => true,
                'public' => true,
                'name' => 'GET',
                'action' => 1,
                'response' => 1,
            ])],
        ])];

        return serialize($config);
    }
}
