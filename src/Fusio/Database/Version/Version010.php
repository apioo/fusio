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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Fusio\Database\VersionInterface;

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

    public function executeUpgrade(Connection $connection)
    {
    }
}
