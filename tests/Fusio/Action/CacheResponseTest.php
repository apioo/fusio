<?php

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;
use PSX\Cache;
use PSX\Cache\Handler\Memory;
use PSX\Test\Environment;

class CacheResponseTest extends \PHPUnit_Extensions_Database_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $cache = new Cache(new Memory());

        $action = new CacheResponse();
        $action->setConnection(Environment::getService('connection'));
        $action->setProcessor(Environment::getService('processor'));
        $action->setCache($cache);

        $parameters = $this->getParameters([
            'action' => 2,
            'expire' => 3600,
        ]);

        $response = $action->handle($this->getRequest(), $parameters, $this->getContext());

        $body = new \stdClass();
        $body->id = 1;
        $body->title = 'foo';
        $body->content = 'bar';
        $body->date = '2015-02-27 19:59:15';

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());

        $item = $cache->getItem(md5(2));

        $this->assertTrue($item->isHit());
        $this->assertEquals($response, $item->get());
    }

    public function testGetForm()
    {
        $action = new CacheResponse();
        $action->setConnection(Environment::getService('connection'));

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
