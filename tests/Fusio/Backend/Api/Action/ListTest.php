<?php

namespace Fusio\Backend\Api\Action;

use PSX\Test\ControllerDbTestCase;

class ListTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/../../../fixture.xml');
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/action/list', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "actions": [
        {
            "name": "Beanstalk-Push",
            "class": "Fusio\\Action\\BeanstalkPush"
        },
        {
            "name": "Cache-Response",
            "class": "Fusio\\Action\\CacheResponse"
        },
        {
            "name": "Composite",
            "class": "Fusio\\Action\\Composite"
        },
        {
            "name": "Condition",
            "class": "Fusio\\Action\\Condition"
        },
        {
            "name": "HTTP-Request",
            "class": "Fusio\\Action\\HttpRequest"
        },
        {
            "name": "Mongo-Fetch-All",
            "class": "Fusio\\Action\\MongoFetchAll"
        },
        {
            "name": "Mongo-Fetch-Row",
            "class": "Fusio\\Action\\MongoFetchRow"
        },
        {
            "name": "Mongo-Insert",
            "class": "Fusio\\Action\\MongoInsert"
        },
        {
            "name": "Mongo-Update",
            "class": "Fusio\\Action\\MongoUpdate"
        },
        {
            "name": "Pipe",
            "class": "Fusio\\Action\\Pipe"
        },
        {
            "name": "RabbitMQ-Push",
            "class": "Fusio\\Action\\RabbitMqPush"
        }
    ]
}
JSON;

        $this->assertEquals(null, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}
