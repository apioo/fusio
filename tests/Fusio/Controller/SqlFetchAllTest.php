<?php

namespace Fusio\Backend\Api\Connection;

use PSX\Test\ControllerDbTestCase;

class SqlFetchAllTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/../fixture.xml');
    }

    public function testGet()
    {
        $body = '{"foo": "bar"}';

        $response = $this->sendRequest('http://127.0.0.1/foo', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b41344388feed85bc362e518387fdc8c81b896bfe5e794131e1469770571d873'
        ), $body);

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{"foo": "bar"}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}

