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

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;

/**
 * StaticResponseTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
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
