<?php

namespace Fusio\Backend\Api\Action;

use PSX\Test\ControllerDbTestCase;

class FormTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/../../../fixture.xml');
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/action/form?class=' . urlencode('Fusio\\Action\\SqlFetchRow'), 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "element": [
        {
            "element": "http:\/\/fusio-project.org\/ns\/2015\/form\/select",
            "options": [
                {
                    "key": 2,
                    "value": "DBAL"
                },
                {
                    "key": 1,
                    "value": "Native-Connection"
                }
            ],
            "name": "connection",
            "title": "Connection"
        },
        {
            "element": "http:\/\/fusio-project.org\/ns\/2015\/form\/textarea",
            "mode": "sql",
            "name": "sql",
            "title": "SQL",
            "help": "The SELECT statment which gets executed. It is possible to access values from the environment with i.e. <code ng-non-bindable>{{ request.uriFragment(\"news_id\")|prepare }}<\/code>. <b>Note you must use the prepare filter for each parameter in order to generate a safe SQL query which uses prepared statments.<\/b>"
        }
    ]
}
JSON;

        $this->assertEquals(null, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}
