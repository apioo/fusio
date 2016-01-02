<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Impl\Action;

use Fusio\Impl\ActionTestCaseTrait;
use Fusio\Impl\App;
use Fusio\Impl\Form\Builder;
use PSX\Data\Record;
use PSX\Http\Response;
use PSX\Test\Environment;

/**
 * HttpRequestTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class HttpRequestTest extends \PHPUnit_Framework_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $http = $this->getMock('PSX\Http', array('request'));

        $http->expects($this->once())
            ->method('request')
            ->with($this->callback(function ($request) {
                /** @var \PSX\Http\RequestInterface $request */
                $this->assertInstanceOf('PSX\Http\RequestInterface', $request);
                $this->assertJsonStringEqualsJsonString('{"foo":"bar"}', (string) $request->getBody());

                return true;
            }))
            ->will($this->returnValue(new Response(200)));

        $action = new HttpRequest();
        $action->setHttp($http);
        $action->setTemplateFactory(Environment::getService('template_factory'));
        $action->setResponse(Environment::getService('response'));

        $parameters = $this->getParameters([
            'url'  => 'http://127.0.0.1/bar',
            'body' => '{{ request.body|json }}',
        ]);

        $body = Record::fromArray([
            'foo' => 'bar'
        ]);

        $response = $action->handle($this->getRequest('POST', [], [], [], $body), $parameters, $this->getContext());

        $body = [
            'success' => true,
            'message' => 'Request successful'
        ];

        $this->assertInstanceOf('Fusio\Engine\ResponseInterface', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action  = new HttpRequest();
        $builder = new Builder();
        $factory = Environment::getService('form_element_factory');

        $action->configure($builder, $factory);

        $this->assertInstanceOf('Fusio\Impl\Form\Container', $builder->getForm());
    }
}
