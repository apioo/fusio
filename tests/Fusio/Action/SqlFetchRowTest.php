<?php

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;
use Fusio\Template\Parser;
use PSX\Test\Environment;

class SqlFetchRowTest extends \PHPUnit_Extensions_Database_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $action = new SqlFetchRow();
        $action->setConnection(Environment::getService('connection'));
        $action->setConnector(Environment::getService('connector'));
        $action->setTemplateParser(new Parser(true, false));

        $parameters = $this->getParameters([
            'connection' => 1,
            'sql'        => 'SELECT * FROM app_news WHERE id = {{ request.uriFragments.get("news_id")|prepare }}',
        ]);

        $response = $action->handle($this->getRequest('GET', array('news_id' => 2)), $parameters, $this->getContext());

        $body = new \stdClass();
        $body->id = 2;
        $body->title = 'bar';
        $body->content = 'foo';
        $body->date = '2015-02-27 19:59:15';

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action = new SqlFetchRow();
        $action->setConnection(Environment::getService('connection'));

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
