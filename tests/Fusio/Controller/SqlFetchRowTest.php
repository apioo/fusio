<?php

namespace Fusio\Backend\Api\Connection;

use PSX\Test\ControllerDbTestCase;

class SqlFetchRowTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/../fixture.xml');
    }

    public function testPost()
    {
        $body = <<<'JSON'
{
    "title": "foo",
    "content": "bar",
    "date": "2015-07-04T13:03:00"
}
JSON;

        $response = $this->sendRequest('http://127.0.0.1/foo', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b41344388feed85bc362e518387fdc8c81b896bfe5e794131e1469770571d873'
        ), $body);

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "id": "1",
    "title": "foo",
    "content": "bar",
    "date": "2015-02-27 19:59:15"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}

