<?php

namespace Fusio\Backend\Api\Schema;

use PSX\Test\ControllerDbTestCase;
use PSX\Test\Environment;

class CollectionTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/../../../fixture.xml');
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/schema', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "totalItems": 2,
    "startIndex": 0,
    "entry": [
        {
            "id": 2,
            "name": "Foo-Schema"
        },
        {
            "id": 1,
            "name": "Passthru"
        }
    ]
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testPost()
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
        	"$ref": "schema:///Foo-Schema"
        }
    }
}
JSON;

        $response = $this->sendRequest('http://127.0.0.1/backend/schema', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'name'   => 'Bar-Schema',
            'source' => $schema,
        ]));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Schema successful created"
}
JSON;

        $this->assertEquals(201, $response->getStatusCode(), $body);
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

        $this->assertEquals(3, $row['id']);
        $this->assertEquals('Bar-Schema', $row['name']);
        $this->assertEquals(null, $row['propertyName']);
        $this->assertEquals($schema, $row['source']);
        $this->assertTrue(!empty($row['cache']));
    }

    public function testPut()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/schema', 'PUT', array(
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
        $response = $this->sendRequest('http://127.0.0.1/backend/schema', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }
}
