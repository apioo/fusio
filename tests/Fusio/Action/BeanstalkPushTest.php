<?php

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;
use Fusio\Parameters;
use PSX\Cache;
use PSX\Cache\Handler\Memory;
use PSX\Data\Object;
use PSX\Http\Response;
use PSX\Test\Environment;

class BeanstalkPushTest extends \PHPUnit_Extensions_Database_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        // connection
        $connection = $this->getMock('Pheanstalk\Pheanstalk', ['useTube', 'put'], [], '', false);

        $connection->expects($this->once())
            ->method('useTube')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue($connection));

        $connection->expects($this->once())
            ->method('put')
            ->with($this->callback(function($body){
                $this->assertJsonStringEqualsJsonString('{"foo": "bar"}', $body);

                return true;
            }))
            ->will($this->returnValue($connection));

        // connector
        $connector = $this->getMock('Fusio\Connector', ['getConnection'], [], '', false);

        $connector->expects($this->once())
            ->method('getConnection')
            ->with($this->equalTo(1))
            ->will($this->returnValue($connection));

        $action = new BeanstalkPush();
        $action->setConnection(Environment::getService('connection'));
        $action->setConnector($connector);

        $parameters = $this->getParameters([
            'connection' => 1,
            'queue'      => 'foo',
        ]);

        $body = new Object([
            'foo' => 'bar'
        ]);

        $response = $action->handle($this->getRequest('POST', [], [], [], $body), $parameters, $this->getContext());

        $body = [
            'success' => true,
            'message' => 'Push was successful'
        ];

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action = new BeanstalkPush();
        $action->setConnection(Environment::getService('connection'));

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
