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

namespace Fusio;

use Fusio\App;
use Fusio\Template\Parser;
use Psr\Http\Message\StreamInterface;
use PSX\Data\Object;
use PSX\Data\RecordInterface;
use PSX\Http\Request as HttpRequest;
use PSX\Test\Environment;
use PSX\Uri;

trait ActionTestCaseTrait
{
    protected static $con;

    protected $connection;

    public function getConnection()
    {
        if (!Environment::hasConnection()) {
            $this->markTestSkipped('No database connection available');
        }

        if (self::$con === null) {
            self::$con = Environment::getService('connection');
        }

        if ($this->connection === null) {
            $this->connection = self::$con;
        }

        return $this->createDefaultDBConnection($this->connection->getWrappedConnection(), Environment::getService('config')->get('psx_sql_db'));
    }

    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/fixture.xml');
    }

    protected function getRequest($method = null, array $uriFragments = array(), array $parameters = array(), array $headers = array(), RecordInterface $parsedBody = null, StreamInterface $rawBody = null)
    {
        return new Request(
            new HttpRequest(new Uri('http://127.0.0.1/foo'), $method === null ? 'GET' : $method, $headers, $rawBody),
            $uriFragments,
            $parameters,
            $parsedBody === null ? new Object() : $parsedBody
        );
    }

    protected function getParameters(array $parameters = array())
    {
        return new Parameters(array_merge([
            Parameters::ACTION_ID => uniqid(),
            Parameters::ACTION_NAME => 'Foo-App',
            Parameters::ACTION_LAST_MODIFIED => '2015-02-22 22:19:07',
        ], $parameters));
    }

    protected function getContext()
    {
        $app = new App();
        $app->setAnonymous(false);
        $app->setId(2);
        $app->setUserId(2);
        $app->setStatus(1);
        $app->setName('Foo-App');
        $app->setUrl('http://google.com');
        $app->setAppKey('5347307d-d801-4075-9aaa-a21a29a448c5');

        return new Context(34, $app);
    }

    protected function getTemplateParser()
    {
        return new Parser(true, false);
    }
}
