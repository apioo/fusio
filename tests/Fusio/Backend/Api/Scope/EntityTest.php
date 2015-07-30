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

namespace Fusio\Backend\Api\Scope;

use Fusio\Fixture;
use PSX\Test\ControllerDbTestCase;
use PSX\Test\Environment;

/**
 * EntityTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class EntityTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/scope/4', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "id": 4,
    "name": "bar",
    "routes": [
        {
            "routeId": 37,
            "allow": true,
            "methods": "GET|POST|PUT|DELETE"
        },
        {
            "routeId": 36,
            "allow": true,
            "methods": "GET|POST|PUT|DELETE"
        }
    ]
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testPost()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/scope/4', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }

    public function testPut()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/scope/4', 'PUT', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'name'   => 'Test',
        ]));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Scope successful updated"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'name')
            ->from('fusio_scope')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(4, $row['id']);
        $this->assertEquals('Test', $row['name']);
    }

    public function testDelete()
    {
        // delete all scope references to successful delete an scope
        Environment::getService('connection')->executeUpdate('DELETE FROM fusio_app_scope WHERE scopeId = :scopeId', ['scopeId' => 4]);
        Environment::getService('connection')->executeUpdate('DELETE FROM fusio_user_scope WHERE scopeId = :scopeId', ['scopeId' => 4]);

        $response = $this->sendRequest('http://127.0.0.1/backend/scope/4', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Scope successful deleted"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id')
            ->from('fusio_scope')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(3, $row['id']);
    }

    public function testDeleteAppScopeAssigned()
    {
        Environment::getService('connection')->executeUpdate('DELETE FROM fusio_user_scope WHERE scopeId = :scopeId', ['scopeId' => 4]);

        $response = $this->sendRequest('http://127.0.0.1/backend/scope/4', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body = (string) $response->getBody();

        $this->assertEquals(500, $response->getStatusCode(), $body);
        $this->assertTrue(strpos($body, 'Scope is assigned to an app') !== false, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id')
            ->from('fusio_scope')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(4, $row['id']);
    }

    public function testDeleteUserScopeAssigned()
    {
        Environment::getService('connection')->executeUpdate('DELETE FROM fusio_app_scope WHERE scopeId = :scopeId', ['scopeId' => 4]);

        $response = $this->sendRequest('http://127.0.0.1/backend/scope/4', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body = (string) $response->getBody();

        $this->assertEquals(500, $response->getStatusCode(), $body);
        $this->assertTrue(strpos($body, 'Scope is assgined to an user') !== false, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id')
            ->from('fusio_scope')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(4, $row['id']);
    }
}
