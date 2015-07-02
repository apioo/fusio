<?php

namespace Fusio\Backend\Api\Scope;

use PSX\Test\ControllerDbTestCase;
use PSX\Test\Environment;

class CollectionTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/../../../fixture.xml');
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
    "totalItems": 3,
    "startIndex": 0,
    "entry": [
        {
            "id": 3,
            "name": "bar"
        },
        {
            "id": 2,
            "name": "foo"
        },
        {
            "id": 1,
            "name": "backend"
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
            'name'   => 'test',
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
            ->select('id', 'name')
            ->from('fusio_scope')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(4, $row['id']);
        $this->assertEquals('test', $row['name']);

        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'scopeId', 'routeId', 'allow', 'methods')
            ->from('fusio_scope_routes')
            ->where('scopeId = :scopeId')
            ->orderBy('id', 'DESC')
            ->getSQL();

        $routes = Environment::getService('connection')->fetchAll($sql, ['scopeId' => 4]);

        $this->assertEquals([[
            'id'      => 38,
            'scopeId' => 4,
            'routeId' => 2,
            'allow'   => 1,
            'methods' => 'GET|POST|PUT|DELETE',
        ], [
            'id'      => 37,
            'scopeId' => 4,
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
