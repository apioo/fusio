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

namespace Fusio\Impl;

use Doctrine\DBAL\Schema\Schema as DbSchema;
use Fusio\Impl\Database\Version;
use PSX\Test\Environment;

/**
 * Fixture
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Fixture
{
    protected static $dataSet;

    public static function getDataSet()
    {
        if (self::$dataSet !== null) {
            return self::$dataSet;
        }

        $version = new Version\Version010();
        $dataSet = array_merge_recursive($version->getInstallInserts(), self::getTestInserts());

        return self::$dataSet = new \PHPUnit_Extensions_Database_DataSet_ArrayDataSet($dataSet);
    }

    protected static function getTestInserts()
    {
        $schemaSource = <<<'JSON'
{
    "id": "http://phpsx.org#",
    "title": "test",
    "type": "object",
    "properties": {
        "title": {
            "type": "string"
        },
        "content": {
            "type": "string"
        },
        "date": {
            "type": "string",
            "format": "date-time"
        }
    }
}
JSON;

        return [
            'fusio_user' => [
                ['status' => 0, 'name' => 'Consumer', 'password' => '$2y$10$XYDj6X1AA0GnA0pi9GMxCumlOfcFjTBE2JtxvAh2RWz/LyeGfO0P6', 'date' => '2015-02-27 19:59:15'],
                ['status' => 2, 'name' => 'Disabled', 'password' => '$2y$10$XYDj6X1AA0GnA0pi9GMxCumlOfcFjTBE2JtxvAh2RWz/LyeGfO0P6', 'date' => '2015-02-27 19:59:15'],
                ['status' => 1, 'name' => 'Developer', 'password' => '$2y$10$XYDj6X1AA0GnA0pi9GMxCumlOfcFjTBE2JtxvAh2RWz/LyeGfO0P6', 'date' => '2015-02-27 19:59:15'],
            ],
            'fusio_action' => [
                ['name' => 'Sql-Fetch-All', 'class' => 'Fusio\Impl\Action\SqlFetchAll', 'config' => serialize(['connection' => 1, 'sql' => 'SELECT * FROM app_news']), 'date' => '2015-02-27 19:59:15'],
                ['name' => 'Sql-Fetch-Row', 'class' => 'Fusio\Impl\Action\SqlFetchRow', 'config' => serialize(['connection' => 1, 'sql' => 'SELECT * FROM app_news']), 'date' => '2015-02-27 19:59:15'],
            ],
            'fusio_app' => [
                ['userId' => 2, 'status' => 1, 'name' => 'Foo-App', 'url' => 'http://google.com', 'appKey' => '5347307d-d801-4075-9aaa-a21a29a448c5', 'appSecret' => '342cefac55939b31cd0a26733f9a4f061c0829ed87dae7caff50feaa55aff23d', 'date' => '2015-02-22 22:19:07'],
                ['userId' => 2, 'status' => 2, 'name' => 'Pending', 'url' => 'http://google.com', 'appKey' => '7c14809c-544b-43bd-9002-23e1c2de6067', 'appSecret' => 'bb0574181eb4a1326374779fe33e90e2c427f28ab0fc1ffd168bfd5309ee7caa', 'date' => '2015-02-22 22:19:07'],
                ['userId' => 2, 'status' => 3, 'name' => 'Deactivated', 'url' => 'http://google.com', 'appKey' => 'f46af464-f7eb-4d04-8661-13063a30826b', 'appSecret' => '17b882987298831a3af9c852f9cd0219d349ba61fcf3fc655ac0f07eece951f9', 'date' => '2015-02-22 22:19:07'],
            ],
            'fusio_connection' => [
                ['name' => 'DBAL', 'class' => 'Fusio\Connection\DBAL', 'config' => serialize(['type' => 'pdo_mysql', 'host' => '127.0.0.1', 'username' => 'root', 'password' => 'foo', 'database' => 'bar'])],
            ],
            'fusio_routes' => [
                ['status' => 1, 'methods' => 'GET|POST|PUT|DELETE', 'path' => '/foo', 'controller' => 'Fusio\Impl\Controller\SchemaApiController', 'config' => 'a:1:{i:0;C:15:"PSX\Data\Record":660:{a:2:{s:4:"name";s:6:"config";s:6:"fields";a:4:{s:6:"active";b:1;s:6:"status";i:4;s:4:"name";s:1:"1";s:7:"methods";a:4:{i:0;C:15:"PSX\Data\Record":106:{a:2:{s:4:"name";s:6:"method";s:6:"fields";a:3:{s:4:"name";s:3:"GET";s:6:"action";i:3;s:8:"response";i:2;}}}i:1;C:15:"PSX\Data\Record":159:{a:2:{s:4:"name";s:6:"method";s:6:"fields";a:6:{s:6:"active";b:1;s:6:"public";b:0;s:4:"name";s:4:"POST";s:6:"action";i:3;s:7:"request";i:2;s:8:"response";i:1;}}}i:2;C:15:"PSX\Data\Record":70:{a:2:{s:4:"name";s:6:"method";s:6:"fields";a:1:{s:4:"name";s:3:"PUT";}}}i:3;C:15:"PSX\Data\Record":73:{a:2:{s:4:"name";s:6:"method";s:6:"fields";a:1:{s:4:"name";s:6:"DELETE";}}}}}}}}'],
            ],
            'fusio_log' => [
                ['appId' => 2, 'routeId' => 1, 'ip' => '127.0.0.1', 'userAgent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'method' => 'GET', 'path' => '/bar', 'header' => 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'body' => 'foobar', 'date' => date('Y-m-d 00:00:00')],
                ['appId' => 2, 'routeId' => 1, 'ip' => '127.0.0.1', 'userAgent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'method' => 'GET', 'path' => '/bar', 'header' => 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'body' => 'foobar', 'date' => date('Y-m-d 00:00:00')],
            ],
            'fusio_log_error' => [
                ['logId' => 1, 'message' => 'Syntax error, malformed JSON', 'trace' => '[trace]', 'file' => '[file]', 'line' => 74],
            ],
            'fusio_schema' => [
                ['name' => 'Foo-Schema', 'source' => $schemaSource, 'cache' => 'C:15:"PSX\Data\Schema":819:{C:36:"PSX\Data\Schema\Property\ComplexType":769:{a:5:{s:10:"properties";a:3:{s:5:"title";C:35:"PSX\Data\Schema\Property\StringType":158:{a:8:{s:9:"minLength";N;s:9:"maxLength";N;s:7:"pattern";N;s:11:"enumeration";N;s:4:"name";s:5:"title";s:11:"description";N;s:8:"required";N;s:9:"reference";N;}}s:7:"content";C:35:"PSX\Data\Schema\Property\StringType":160:{a:8:{s:9:"minLength";N;s:9:"maxLength";N;s:7:"pattern";N;s:11:"enumeration";N;s:4:"name";s:7:"content";s:11:"description";N;s:8:"required";N;s:9:"reference";N;}}s:4:"date";C:37:"PSX\Data\Schema\Property\DateTimeType":157:{a:8:{s:9:"minLength";N;s:9:"maxLength";N;s:7:"pattern";N;s:11:"enumeration";N;s:4:"name";s:4:"date";s:11:"description";N;s:8:"required";N;s:9:"reference";N;}}}s:4:"name";s:4:"test";s:11:"description";N;s:8:"required";N;s:9:"reference";N;}}}'],
            ],
            'fusio_scope' => [
                ['name' => 'foo'],
                ['name' => 'bar'],
            ],
            'fusio_app_scope' => [
                ['appId' => 2, 'scopeId' => 2],
                ['appId' => 2, 'scopeId' => 3],
                ['appId' => 2, 'scopeId' => 4],
            ],
            'fusio_app_token' => [
                ['appId' => 1, 'userId' => 1, 'status' => 1, 'token' => 'da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf', 'scope' => 'backend', 'ip' => '127.0.0.1', 'date' => '2015-06-25 22:49:09'],
                ['appId' => 2, 'userId' => 2, 'status' => 1, 'token' => 'b41344388feed85bc362e518387fdc8c81b896bfe5e794131e1469770571d873', 'scope' => 'bar', 'ip' => '127.0.0.1', 'date' => '2015-06-25 22:49:09'],
            ],
            'fusio_scope_routes' => [
                ['scopeId' => 4, 'routeId' => 40, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
                ['scopeId' => 4, 'routeId' => 41, 'allow' => 1, 'methods' => 'GET|POST|PUT|DELETE'],
            ],
            'fusio_user_scope' => [
                ['userId' => 2, 'scopeId' => 3],
                ['userId' => 2, 'scopeId' => 4],
            ],
            'app_news' => [
                ['title' => 'foo', 'content' => 'bar', 'date' => '2015-02-27 19:59:15'],
                ['title' => 'bar', 'content' => 'foo', 'date' => '2015-02-27 19:59:15'],
            ],
        ];
    }
}
