<?php

namespace Fusio\Backend\Api\Schema;

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
        $response = $this->sendRequest('http://127.0.0.1/backend/schema/1', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "id": 1,
    "name": "Passthru",
    "source": "{}"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testPost()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/schema/2', 'POST', array(
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
        $schema = <<<'JSON'
{
    "id": "http://phpsx.org#",
    "title": "test",
    "type": "object",
    "properties": {
        "title": {
            "type": "string"
        },
        "foo": {
            "type": "string"
        },
        "bar": {
            "type": "string"
        }
    }
}
JSON;

        $response = $this->sendRequest('http://127.0.0.1/backend/schema/2', 'PUT', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'name'   => 'Test-Schema',
            'source' => $schema,
        ]));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Schema successful updated"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'name', 'propertyName', 'source', 'cache')
            ->from('fusio_schema')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(2, $row['id']);
        $this->assertEquals('Test-Schema', $row['name']);
        $this->assertEquals(null, $row['propertyName']);
        $this->assertEquals($schema, $row['source']);
        $this->assertTrue(!empty($row['cache']));
    }

    public function testDelete()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/schema/2', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Schema successful deleted"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id')
            ->from('fusio_schema')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(1, $row['id']);
    }
}
