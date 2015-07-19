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
use Fusio\DbTestCase;
use PSX\Test\Environment;

/**
 * SqlFetchAllTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SqlFetchAllTest extends DbTestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $action = new SqlFetchAll();
        $action->setConnection(Environment::getService('connection'));
        $action->setConnector(Environment::getService('connector'));
        $action->setTemplateParser($this->getTemplateParser());

        $parameters = $this->getParameters([
            'connection'   => 1,
            'propertyName' => 'foo',
            'sql'          => 'SELECT * FROM app_news ORDER BY id DESC',
        ]);

        $response = $action->handle($this->getRequest(), $parameters, $this->getContext());

        $result = [];
        $result[] = [
            'id' => 2,
            'title' => 'bar',
            'content' => 'foo',
            'date' => '2015-02-27 19:59:15',
        ];
        $result[] = [
            'id' => 1,
            'title' => 'foo',
            'content' => 'bar',
            'date' => '2015-02-27 19:59:15',
        ];

        $body = new \stdClass();
        $body->foo = $result;

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action = new SqlFetchAll();
        $action->setConnection(Environment::getService('connection'));

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
