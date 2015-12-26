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

namespace Fusio\Impl\Backend\Api\Scope;

use Fusio\Impl\Fixture;
use PSX\Test\ControllerDbTestCase;
use PSX\Test\Environment;

/**
 * CollectionTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class CollectionTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/scope', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "totalItems": 5,
    "startIndex": 0,
    "entry": [
        {
            "id": 5,
            "name": "bar",
            "description": "Bar access"
        },
        {
            "id": 4,
            "name": "foo",
            "description": "Foo access"
        },
        {
            "id": 3,
            "name": "authorization",
            "description": "Authorization API endpoint"
        },
        {
            "id": 2,
            "name": "consumer",
            "description": "Consumer API endpoint"
        },
        {
            "id": 1,
            "name": "backend",
            "description": "Access to the backend API"
        }
    ]
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testPost()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/scope', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'name'        => 'test',
            'description' => 'Test description',
            'routes' => [[
                'routeId' => 1,
                'allow'   => 1,
                'methods' => 'GET|POST|PUT|DELETE',
            ], [
                'routeId' => 2,
                'allow'   => 1,
                'methods' => 'GET|POST|PUT|DELETE',
            ]]
        ]));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Scope successful created"
}
JSON;

        $this->assertEquals(201, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'name', 'description')
            ->from('fusio_scope')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(6, $row['id']);
        $this->assertEquals('test', $row['name']);
        $this->assertEquals('Test description', $row['description']);

        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'scopeId', 'routeId', 'allow', 'methods')
            ->from('fusio_scope_routes')
            ->where('scopeId = :scopeId')
            ->orderBy('id', 'DESC')
            ->getSQL();

        $routes = Environment::getService('connection')->fetchAll($sql, ['scopeId' => 6]);

        $this->assertEquals([[
            'id'      => 43,
            'scopeId' => 6,
            'routeId' => 2,
            'allow'   => 1,
            'methods' => 'GET|POST|PUT|DELETE',
        ], [
            'id'      => 42,
            'scopeId' => 6,
            'routeId' => 1,
            'allow'   => 1,
            'methods' => 'GET|POST|PUT|DELETE',
        ]], $routes);
    }

    public function testPut()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/scope', 'PUT', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }

    public function testDelete()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/scope', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }
}
