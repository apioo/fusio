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

namespace Fusio\Impl\Backend\Api\User;

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
        $response = $this->sendRequest('http://127.0.0.1/backend/user', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body = (string) $response->getBody();
        $body = preg_replace('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z/m', '[datetime]', $body);

        $expect = <<<'JSON'
{
    "totalResults": 4,
    "startIndex": 0,
    "entry": [
        {
            "id": 4,
            "status": 1,
            "name": "Developer",
            "date": "[datetime]"
        },
        {
            "id": 3,
            "status": 2,
            "name": "Disabled",
            "date": "[datetime]"
        },
        {
            "id": 2,
            "status": 0,
            "name": "Consumer",
            "date": "[datetime]"
        },
        {
            "id": 1,
            "status": 1,
            "name": "Administrator",
            "date": "[datetime]"
        }
    ]
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testPost()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/user', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'status' => 0,
            'name'   => 'test',
            'scopes' => ['foo', 'bar'],
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body);
        $pw   = isset($data->password) ? $data->password : null;
        $body = str_replace(trim(json_encode($pw), '"'), '[password]', $body);

        $expect = <<<'JSON'
{
    "success": true,
    "message": "User successful created",
    "password": "[password]"
}
JSON;

        $this->assertEquals(201, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'status', 'name')
            ->from('fusio_user')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(6, $row['id']);
        $this->assertEquals(0, $row['status']);
        $this->assertEquals('test', $row['name']);

        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'userId', 'scopeId')
            ->from('fusio_user_scope')
            ->where('userId = :userId')
            ->orderBy('id', 'DESC')
            ->getSQL();

        $routes = Environment::getService('connection')->fetchAll($sql, ['userId' => 6]);

        $this->assertEquals([[
            'id'      => 9,
            'userId'  => 6,
            'scopeId' => 4,
        ], [
            'id'      => 8,
            'userId'  => 6,
            'scopeId' => 5,
        ]], $routes);
    }

    public function testPut()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/user', 'PUT', array(
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
        $response = $this->sendRequest('http://127.0.0.1/backend/user', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }
}
