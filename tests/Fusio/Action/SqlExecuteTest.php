<?php

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;
use PSX\Data\Object;
use PSX\Test\Environment;

class SqlExecuteTest extends \PHPUnit_Extensions_Database_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $action = new SqlExecute();
        $action->setConnection(Environment::getService('connection'));
        $action->setConnector(Environment::getService('connector'));
        $action->setTemplateParser($this->getTemplateParser());

        $parameters = $this->getParameters([
            'connection' => 1,
            'sql'        => 'INSERT INTO app_news (title, content, date) VALUES ({{ body.get("title")|prepare }}, {{ body.get("content")|prepare }}, {{ "now"|date("Y-m-d H:i:s")|prepare }})',
        ]);

        $body = new Object([
            'title'   => 'lorem',
            'content' => 'ipsum'
        ]);

        $response = $action->handle($this->getRequest('GET', [], [], [], $body), $parameters, $this->getContext());

        $body = [];
        $body['success'] = true;
        $body['message'] = 'Execution was successful';

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());

        $row = Environment::getService('connection')->fetchAssoc('SELECT * FROM app_news ORDER BY id DESC');

        $this->assertEquals([
            'id' => 3,
            'title' => 'lorem',
            'content' => 'ipsum',
            'date' => date('Y-m-d H:i:s'),
        ], $row);
    }

    public function testGetForm()
    {
        $action = new SqlExecute();
        $action->setConnection(Environment::getService('connection'));

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
