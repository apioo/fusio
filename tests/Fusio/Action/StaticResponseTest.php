<?php

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;

class StaticResponseTest extends \PHPUnit_Framework_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $action = new StaticResponse();
        $action->setTemplateParser($this->getTemplateParser());

        $parameters = $this->getParameters([
            'statusCode' => 200,
            'response' => '{"foo": "bar"}',
        ]);

        $response = $action->handle($this->getRequest(), $parameters, $this->getContext());

        $body = new \stdClass();
        $body->foo = 'bar';

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    /**
     * @expectedException \PSX\Exception
     */
    public function testHandleInvalidResponseFormat()
    {
        $action = new StaticResponse();
        $action->setTemplateParser($this->getTemplateParser());

        $parameters = $this->getParameters([
            'statusCode' => 200,
            'response' => '<foo />',
        ]);

        $action->handle($this->getRequest(), $parameters, $this->getContext());
    }

    public function testGetForm()
    {
        $action = new StaticResponse();

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
