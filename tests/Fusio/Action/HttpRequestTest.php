<?php

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;
use PSX\Data\Object;
use PSX\Http\Response;
use PSX\Test\Environment;

class HttpRequestTest extends \PHPUnit_Framework_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $http = $this->getMock('PSX\Http', array('request'));

        $http->expects($this->once())
            ->method('request')
            ->with($this->callback(function($request){
                /** @var \PSX\Http\RequestInterface $request */
                $this->assertInstanceOf('PSX\Http\RequestInterface', $request);
                $this->assertJsonStringEqualsJsonString('{"foo":"bar"}', (string) $request->getBody());

                return true;
            }))
            ->will($this->returnValue(new Response(200)));

        $action = new HttpRequest();
        $action->setHttp($http);
        $action->setTemplateParser($this->getTemplateParser());

        $parameters = $this->getParameters([
            'url'  => 'http://127.0.0.1/bar',
            'body' => '{{ request.body|json }}',
        ]);

        $body = new Object([
            'foo' => 'bar'
        ]);

        $response = $action->handle($this->getRequest('POST', [], [], [], $body), $parameters, $this->getContext());

        $body = [
            'success' => true,
            'message' => 'Request successful'
        ];

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action = new HttpRequest();

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
