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
use PSX\Test\Environment;
use PSX\Data\Object;

/**
 * ValidatorTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ValidatorTest extends DbTestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $action = new Validator();
        $action->setProcessor(Environment::getService('processor'));
        $action->setCache(Environment::getService('cache'));
        $action->setValidateServiceContainer(Environment::getService('validate_service_container'));

        $rules = <<<YAML
/~query/foo: filter.alnum(value)
/~path/bar: filter.alnum(value)
/id: database.rowExists('Native-Connection', 'fusio_user', 'id', value)
/title: filter.alnum(value)
/author/name: filter.alnum(value)
YAML;

        $parameters = $this->getParameters([
            'action' => 3,
            'rules'  => $rules,
        ]);

        $body = new Object([
            'id'     => 1,
            'title'  => 'foo',
            'author' => new Object([
                'name' => 'bar'
            ]),
        ]);

        $request = $this->getRequest(
            null, 
            ['bar' => 'foo'], 
            ['foo' => 'bar'],
            [],
            $body
        );

        $response = $action->handle($request, $parameters, $this->getContext());

        $body = new \stdClass();
        $body->id = 1;
        $body->title = 'foo';
        $body->content = 'bar';
        $body->date = '2015-02-27 19:59:15';

        $this->assertInstanceOf('Fusio\Engine\ResponseInterface', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    /**
     * @expectedException PSX\Validate\ValidationException
     * @expectedExceptionMessage /~path/bar contains an invalid value
     */
    public function testHandleInvalidPath()
    {
        $body = new Object([
            'id'     => 1,
            'title'  => 'foo',
            'author' => new Object([
                'name' => 'bar'
            ]),
        ]);

        $request = $this->getRequest(
            null, 
            ['bar' => '!foo'], 
            ['foo' => 'bar'],
            [],
            $body
        );

        $this->handle($request, $body);
    }

    /**
     * @expectedException PSX\Validate\ValidationException
     * @expectedExceptionMessage /~query/foo contains an invalid value
     */
    public function testHandleInvalidQuery()
    {
        $body = new Object([
            'id'     => 1,
            'title'  => 'foo',
            'author' => new Object([
                'name' => 'bar'
            ]),
        ]);

        $request = $this->getRequest(
            null, 
            ['bar' => 'foo'], 
            ['foo' => '!bar'],
            [],
            $body
        );

        $this->handle($request, $body);
    }

    /**
     * @expectedException PSX\Validate\ValidationException
     * @expectedExceptionMessage /id contains an invalid value
     */
    public function testHandleInvalidBodyId()
    {
        $body = new Object([
            'id'     => 8,
            'title'  => 'foo',
            'author' => new Object([
                'name' => 'bar'
            ]),
        ]);

        $request = $this->getRequest(
            null, 
            ['bar' => 'foo'], 
            ['foo' => 'bar'],
            [],
            $body
        );

        $this->handle($request, $body);
    }

    /**
     * @expectedException PSX\Validate\ValidationException
     * @expectedExceptionMessage /title contains a custom error message
     */
    public function testHandleInvalidBodyTitle()
    {
        $body = new Object([
            'id'     => 1,
            'title'  => '!foo',
            'author' => new Object([
                'name' => 'bar'
            ]),
        ]);

        $request = $this->getRequest(
            null, 
            ['bar' => 'foo'], 
            ['foo' => 'bar'],
            [],
            $body
        );

        $this->handle($request, $body);
    }

    /**
     * @expectedException PSX\Validate\ValidationException
     * @expectedExceptionMessage /author/name contains an invalid value
     */
    public function testHandleInvalidBodyAuthorName()
    {
        $body = new Object([
            'id'     => 1,
            'title'  => 'foo',
            'author' => new Object([
                'name' => '!bar'
            ]),
        ]);

        $request = $this->getRequest(
            null, 
            ['bar' => 'foo'], 
            ['foo' => 'bar'],
            [],
            $body
        );

        $this->handle($request, $body);
    }

    public function testGetForm()
    {
        $action  = new Validator();
        $builder = new Builder();
        $factory = Environment::getService('form_element_factory');

        $action->configure($builder, $factory);

        $this->assertInstanceOf('Fusio\Impl\Form\Container', $builder->getForm());
    }

    protected function handle($request, $body)
    {
        $action = new Validator();
        $action->setProcessor(Environment::getService('processor'));
        $action->setCache(Environment::getService('cache'));
        $action->setValidateServiceContainer(Environment::getService('validate_service_container'));

        $rules = <<<YAML
/~query/foo: filter.alnum(value)
/~path/bar: filter.alnum(value)
/id: database.rowExists('Native-Connection', 'fusio_user', 'id', value)
/title: 
    rule: filter.alnum(value)
    message: %s contains a custom error message
/author/name: filter.alnum(value)
YAML;

        $parameters = $this->getParameters([
            'action' => 3,
            'rules'  => $rules,
        ]);

        $response = $action->handle($request, $parameters, $this->getContext());
    }
}
