<?php

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;
use Fusio\Template\Parser;
use PSX\Test\Environment;

class SqlFetchAllTest extends \PHPUnit_Extensions_Database_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $action = new SqlFetchAll();
        $action->setConnection(Environment::getService('connection'));
        $action->setConnector(Environment::getService('connector'));
        $action->setTemplateParser($this->getTemplateParser());

        $parameters = $this->getParameters([
            'connection'   => 1,
            'propertyName' => 'foo',
            'sql'          => 'SELECT * FROM app_news ORDER BY id DESC',
        ]);

        $response = $action->handle($this->getRequest(), $parameters, $this->getContext());

        $result = [];
        $result[] = [
            'id' => 2,
            'title' => 'bar',
            'content' => 'foo',
            'date' => '2015-02-27 19:59:15',
        ];
        $result[] = [
            'id' => 1,
            'title' => 'foo',
            'content' => 'bar',
            'date' => '2015-02-27 19:59:15',
        ];

        $body = new \stdClass();
        $body->foo = $result;

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action = new SqlFetchAll();
        $action->setConnection(Environment::getService('connection'));

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
