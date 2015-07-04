<?php

namespace Fusio\Backend\Api\Schema;

use PSX\Test\ControllerDbTestCase;

class PreviewTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/../../../fixture.xml');
    }

    /**
     * @TODO at the moment we cant run this test since the serialized cache
     * contains null bytes which we cant insert into the xml fixture
     */
    public function testGet()
    {
        $this->markTestSkipped('Cache fixture not available');

        $response = $this->sendRequest('http://127.0.0.1/backend/schema/preview/2', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
}
JSON;

        $this->assertEquals(null, $response->getStatusCode(), $body);
        $this->assertXmlStringEqualsXmlString($expect, $body, $body);
    }
}
