<?php

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;
use PSX\Data\Object;
use PSX\Test\Environment;

class ConditionTest extends \PHPUnit_Extensions_Database_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $action = new Condition();
        $action->setConnection(Environment::getService('connection'));
        $action->setProcessor(Environment::getService('processor'));
        $action->setCache(Environment::getService('cache'));

        $expression = 'rateLimit.getRequestsPerMonth() == 0 && ';
        $expression.= 'rateLimit.getRequestsPerDay() == 0 && ';
        $expression.= 'rateLimit.getRequestsOfRoutePerMonth() == 0 && ';
        $expression.= 'rateLimit.getRequestsOfRoutePerDay() == 0 && ';
        $expression.= 'app.getName() == "Foo-App" && ';
        $expression.= 'uriFragments.get("news_id") == 1 && ';
        $expression.= 'parameters.get("count") == 4 && ';
        $expression.= 'body.get("foo") == "bar" ';

        $parameters = $this->getParameters([
            'condition' => $expression,
            'true'      => 2,
            'false'     => 0,
        ]);

        $body = new Object([
            'foo' => 'bar'
        ]);

        $response = $action->handle($this->getRequest('POST', ['news_id' => 1], ['count' => 4], [], $body), $parameters, $this->getContext());

        $body = new \stdClass();
        $body->id = 1;
        $body->title = 'foo';
        $body->content = 'bar';
        $body->date = '2015-02-27 19:59:15';

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action = new Condition();
        $action->setConnection(Environment::getService('connection'));

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
