<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
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
use Fusio\Impl\DbTestCase;
use Fusio\Impl\Form\Builder;
use PSX\Data\Record;
use PSX\Test\Environment;

/**
 * ProcessorTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ProcessorTest extends DbTestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $action = new Processor();
        $action->setProcessor(Environment::getService('processor'));

        $parameters = $this->getParameters([
            'process' => $this->getProcess(),
        ]);

        $body = Record::fromArray([
            'news_id' => 2,
            'title'   => 'foo',
        ]);

        $request  = $this->getRequest(null, [], [], [], $body);
        $response = $action->handle($request, $parameters, $this->getContext());

        $body = new \stdClass();
        $body->id = 2;
        $body->title = 'bar';
        $body->content = 'foo';
        $body->date = '2015-02-27 19:59:15';

        $this->assertInstanceOf('Fusio\Engine\ResponseInterface', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testHandleErrorCase()
    {
        $action = new Processor();
        $action->setProcessor(Environment::getService('processor'));

        $parameters = $this->getParameters([
            'process' => $this->getProcess(),
        ]);

        $body = Record::fromArray([
            'news_id' => 2,
            'title'   => 'bar',
        ]);

        $request  = $this->getRequest(null, [], [], [], $body);
        $response = $action->handle($request, $parameters, $this->getContext());

        $body = new \stdClass();
        $body->error = 'foo';

        $this->assertInstanceOf('Fusio\Engine\ResponseInterface', $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    /**
     * @expectedException PSX\Validate\ValidationException
     * @expectedExceptionMessage /title contains an invalid value
     */
    public function testHandleValidatorError()
    {
        $action = new Processor();
        $action->setProcessor(Environment::getService('processor'));

        $parameters = $this->getParameters([
            'process' => $this->getProcess(),
        ]);

        $body = Record::fromArray([
            'news_id' => 2,
            'title'   => 'b$ar',
        ]);

        $request  = $this->getRequest(null, [], [], [], $body);
        $response = $action->handle($request, $parameters, $this->getContext());

        $body = new \stdClass();
        $body->error = 'foo';

        $this->assertInstanceOf('Fusio\Engine\ResponseInterface', $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action  = new Processor();
        $builder = new Builder();
        $factory = Environment::getService('form_element_factory');

        $action->configure($builder, $factory);

        $this->assertInstanceOf('Fusio\Impl\Form\Container', $builder->getForm());
    }

    protected function getProcess()
    {
        return <<<'YAML'
Fusio\Impl\Action\Validator:
    "id": validator
    "rules": |
        /news_id: database.rowExists('Native-Connection', 'fusio_user', 'id', value)
        /title: filter.alnum(value)
    "action": 
        transform

Fusio\Impl\Action\Transform:
    "id": transform
    "patch": |
        [
            { "op": "add", "path": "/foo", "value": "bar" }
        ]
    "action": 
        title-check

Fusio\Impl\Action\Condition:
    "id": title-check
    "condition": body.get("/title") == "foo" && body.get("/foo") == "bar"
    "true": select-all
    "false": fault-response

Fusio\Impl\Action\StaticResponse:
    "id": fault-response
    "statusCode": 500,
    "response": |
        {"error": "foo"}

Fusio\Impl\Action\SqlFetchRow:
    "id": select-all
    "connection": 1
    "sql": SELECT * FROM app_news WHERE id = {{ body.get("/news_id")|prepare }}

YAML;
    }
}
