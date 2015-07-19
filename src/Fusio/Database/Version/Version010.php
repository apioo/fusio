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

namespace Fusio\Database\Version;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Fusio\Database\VersionInterface;
use PSX\OpenSsl;
use PSX\Util\Uuid;

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
        $actionTable->addColumn('name', 'string', array('length' => 64));
        $actionTable->addColumn('class', 'string', array('length' => 255));
        $actionTable->addColumn('config', 'blob', array('notnull' => false));
        $actionTable->addColumn('date', 'datetime');
        $actionTable->setPrimaryKey(array('id'));
        $actionTable->addUniqueIndex(array('name'));

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
        $appTable->addUniqueIndex(array('name'));
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

        $connectionTable = $schema->createTable('fusio_connection');
        $connectionTable->addColumn('id', 'integer', array('autoincrement' => true));
        $connectionTable->addColumn('name', 'string', array('length' => 64));
        $connectionTable->addColumn('class', 'string', array('length' => 255));
        $connectionTable->addColumn('config', 'blob', array('notnull' => false));
        $connectionTable->setPrimaryKey(array('id'));
        $connectionTable->addUniqueIndex(array('name'));

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

        $routesTable = $schema->createTable('fusio_routes');
        $routesTable->addColumn('id', 'integer', array('autoincrement' => true));
        $routesTable->addColumn('status', 'integer', array('default' => 1));
        $routesTable->addColumn('methods', 'string', array('length' => 64));
        $routesTable->addColumn('path', 'string', array('length' => 255));
        $routesTable->addColumn('controller', 'string', array('length' => 255));
        $routesTable->addColumn('config', 'blob', array('notnull' => false));
        $routesTable->setPrimaryKey(array('id'));
        $routesTable->addUniqueIndex(array('path'));

        $schemaTable = $schema->createTable('fusio_schema');
        $schemaTable->addColumn('id', 'integer', array('autoincrement' => true));
        $schemaTable->addColumn('name', 'string', array('length' => 64));
        $schemaTable->addColumn('propertyName', 'string', array('length' => 64, 'notnull' => false));
        $schemaTable->addColumn('source', 'text');
        $schemaTable->addColumn('cache', 'blob');
        $schemaTable->setPrimaryKey(array('id'));
        $schemaTable->addUniqueIndex(array('name'));

        $scopeTable = $schema->createTable('fusio_scope');
        $scopeTable->addColumn('id', 'integer', array('autoincrement' => true));
        $scopeTable->addColumn('name', 'string', array('length' => 32));
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

    protected function getInstallInserts()
    {
        $now       = new DateTime();
        $appKey    = Uuid::pseudoRandom();
        $appSecret = hash('sha256', OpenSsl::randomPseudoBytes(256));
        $password  = \password_hash(sha1(mcrypt_create_iv(40)), PASSWORD_DEFAULT);

        $passthruSource = [
            'id' => 'http://fusio-project.org',
            'title' => 'passthru',
            'type' => 'object',
            'properties' => new \stdClass(),
        ];

        return [
            'fusio_user' => [
                ['status' => 1, 'name' => 'Administrator', 'password' => $password, 'date' => $now->format('Y-m-d H:i:s')],
            ],
            'fusio_app' => [
                ['userId' => 1, 'status' => 1, 'name' => 'Backend', 'url' => 'http://fusio-project.org', 'appKey' => $appKey, 'appSecret' => $appSecret, 'date' => $now->format('Y-m-d H:i:s')],
            ],
            'fusio_connection' => [
                ['name' => 'Native-Connection', 'class' => 'Fusio\Connection\Native']
            ],
            'fusio_scope' => [
                ['name' => 'backend']
            ],
            'fusio_schema' => [
                ['name' => 'Passthru', 'source' => json_encode($passthruSource), 'cache' => 'C:15:"PSX\Data\Schema":162:{C:36:"PSX\Data\Schema\Property\ComplexType":112:{a:5:{s:10:"properties";a:0:{}s:4:"name";s:8:"passthru";s:11:"description";N;s:8:"required";N;s:9:"reference";N;}}}']
            ],
            'fusio_routes' => [
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend',                             'controller' => 'Fusio\Backend\Application\Index'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/action',                      'controller' => 'Fusio\Backend\Api\Action\Collection'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/action/list',                 'controller' => 'Fusio\Backend\Api\Action\ListActions::doIndex'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/action/form',                 'controller' => 'Fusio\Backend\Api\Action\ListActions::doDetail'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/action/:action_id',           'controller' => 'Fusio\Backend\Api\Action\Entity'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/app',                         'controller' => 'Fusio\Backend\Api\App\Collection'],
                ['status' => 1, 'methods' => 'DELETE',              'path' => '/backend/app/:app_id/token/:token_id', 'controller' => 'Fusio\Backend\Api\App\Token::doRemove'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/app/:app_id',                 'controller' => 'Fusio\Backend\Api\App\Entity'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/connection',                  'controller' => 'Fusio\Backend\Api\Connection\Collection'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/connection/form',             'controller' => 'Fusio\Backend\Api\Connection\ListConnections::doDetail'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/connection/list',             'controller' => 'Fusio\Backend\Api\Connection\ListConnections::doIndex'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/connection/:connection_id',   'controller' => 'Fusio\Backend\Api\Connection\Entity'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/log',                         'controller' => 'Fusio\Backend\Api\Log\Collection'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/log/:log_id',                 'controller' => 'Fusio\Backend\Api\Log\Entity'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/routes',                      'controller' => 'Fusio\Backend\Api\Routes\Collection'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/routes/:route_id',            'controller' => 'Fusio\Backend\Api\Routes\Entity'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/schema',                      'controller' => 'Fusio\Backend\Api\Schema\Collection'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/schema/:schema_id',           'controller' => 'Fusio\Backend\Api\Schema\Entity'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/schema/preview/:schema_id',   'controller' => 'Fusio\Backend\Api\Schema\Preview'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/scope',                       'controller' => 'Fusio\Backend\Api\Scope\Collection'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/scope/:scope_id',             'controller' => 'Fusio\Backend\Api\Scope\Entity'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/user',                        'controller' => 'Fusio\Backend\Api\User\Collection'],
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/backend/user/:user_id',               'controller' => 'Fusio\Backend\Api\User\Entity'],

                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/dashboard/incoming_requests', 'controller' => 'Fusio\Backend\Api\Dashboard\IncomingRequests'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/dashboard/most_used_routes',  'controller' => 'Fusio\Backend\Api\Dashboard\MostUsedRoutes'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/dashboard/most_used_apps',    'controller' => 'Fusio\Backend\Api\Dashboard\MostUsedApps'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/dashboard/latest_requests',   'controller' => 'Fusio\Backend\Api\Dashboard\LatestRequests'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/dashboard/latest_apps',       'controller' => 'Fusio\Backend\Api\Dashboard\LatestApps'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/backend/dashboard/latest_users',      'controller' => 'Fusio\Backend\Api\Dashboard\LatestUsers'],

                ['status' => 1, 'methods' => 'GET|POST',            'path' => '/backend/token',                       'controller' => 'Fusio\Backend\Authorization\Token'],
                ['status' => 1, 'methods' => 'POST',                'path' => '/authorization/revoke',                'controller' => 'Fusio\Authorization\Revoke'],
                ['status' => 1, 'methods' => 'GET|POST',            'path' => '/authorization/token',                 'controller' => 'Fusio\Authorization\Token'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/authorization/whoami',                'controller' => 'Fusio\Authorization\Whoami'],

                ['status' => 1, 'methods' => 'GET',                 'path' => '/doc',                                 'controller' => 'PSX\Controller\Tool\DocumentationController::doIndex'],
                ['status' => 1, 'methods' => 'GET',                 'path' => '/doc/:version/*path',                  'controller' => 'PSX\Controller\Tool\DocumentationController::doDetail'],
            ],
            'fusio_app_scope' => [
                ['appId' => 1, 'scopeId' => 1]
            ],
            'fusio_scope_routes' => [
                ['scopeId' => 1, 'routeId' => 1,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 2,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 3,  'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 4,  'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 5,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 6,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 7,  'allow' => 1, 'methods' => 'DELETE'],
                ['scopeId' => 1, 'routeId' => 8,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 9,  'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 10, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 11, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 12, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 13, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 14, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 15, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 16, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 17, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 18, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 19, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 20, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 21, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 22, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 23, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 1, 'routeId' => 24, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 25, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 26, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 27, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 28, 'allow' => 1, 'methods' => 'GET'],
                ['scopeId' => 1, 'routeId' => 29, 'allow' => 1, 'methods' => 'GET'],
            ],
            'fusio_user_scope' => [
                ['userId' => 1, 'scopeId' => 1]
            ],
        ];
    }
}
