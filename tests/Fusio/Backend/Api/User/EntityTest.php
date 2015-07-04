<?php

namespace Fusio\Backend\Api\User;

use PSX\Test\ControllerDbTestCase;
use PSX\Test\Environment;

class EntityTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/../../../fixture.xml');
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/user/2', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "id": 2,
    "status": 0,
    "name": "consumer",
    "scopes": [
        "backend",
        "foo"
    ],
    "date": "2015-02-27T19:59:15Z"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testPost()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/user/3', 'POST', array(
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
        $response = $this->sendRequest('http://127.0.0.1/backend/user/3', 'PUT', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'status' => 1,
            'name'   => 'bar',
            'scopes' => ['bar'],
        ]));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "User successful updated"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
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

        $this->assertEquals(3, $row['id']);
        $this->assertEquals(1, $row['status']);
        $this->assertEquals('bar', $row['name']);

        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'userId', 'scopeId')
            ->from('fusio_user_scope')
            ->where('userId = :userId')
            ->orderBy('id', 'DESC')
            ->getSQL();

        $routes = Environment::getService('connection')->fetchAll($sql, ['userId' => 3]);

        $this->assertEquals([[
            'id'      => 3,
            'userId'  => 3,
            'scopeId' => 3,
        ]], $routes);
    }

    public function testDelete()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/user/3', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "User successful deleted"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id')
            ->from('fusio_user')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(2, $row['id']);
    }
}
