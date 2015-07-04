<?php

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;
use PSX\Data\Object;
use PSX\Test\Environment;

class RabbitMqPushTest extends \PHPUnit_Framework_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        // channel
        $channel = $this->getMock('PhpAmqpLib\Channel\AMQPChannel', ['basic_publish'], [], '', false);

        $channel->expects($this->once())
            ->method('basic_publish')
            ->with($this->callback(function($message){
                /** @var \PhpAmqpLib\Message\AMQPMessage $message */
                $this->assertInstanceOf('PhpAmqpLib\Message\AMQPMessage', $message);
                $this->assertEquals(['content_type' => 'application/json', 'delivery_mode' => 2], $message->get_properties());
                $this->assertJsonStringEqualsJsonString('{"foo": "bar"}', $message->body);

                return true;
            }), $this->equalTo(''), $this->equalTo('foo'));

        // connection
        $connection = $this->getMock('PhpAmqpLib\Connection\AMQPStreamConnection', ['channel'], [], '', false);

        $connection->expects($this->once())
            ->method('channel')
            ->will($this->returnValue($channel));

        // connector
        $connector = $this->getMock('Fusio\Connector', ['getConnection'], [], '', false);

        $connector->expects($this->once())
            ->method('getConnection')
            ->with($this->equalTo(1))
            ->will($this->returnValue($connection));

        $action = new RabbitMqPush();
        $action->setConnector($connector);

        $parameters = $this->getParameters([
            'connection' => 1,
            'queue'      => 'foo',
        ]);

        $response = $action->handle($this->getRequest('POST', [], [], [], new Object(['foo' => 'bar'])), $parameters, $this->getContext());

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
        $action = new RabbitMqPush();
        $action->setConnection(Environment::getService('connection'));

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
