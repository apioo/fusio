<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Action;

use Fusio\ActionTestCaseTrait;
use Fusio\App;
use PSX\Test\Environment;

class SqlFetchRowTest extends \PHPUnit_Extensions_Database_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $action = new SqlFetchRow();
        $action->setConnection(Environment::getService('connection'));
        $action->setConnector(Environment::getService('connector'));
        $action->setTemplateParser($this->getTemplateParser());

        $parameters = $this->getParameters([
            'connection' => 1,
            'sql'        => 'SELECT * FROM app_news WHERE id = {{ request.uriFragments.get("news_id")|prepare }}',
        ]);

        $response = $action->handle($this->getRequest('GET', ['news_id' => 2]), $parameters, $this->getContext());

        $body = new \stdClass();
        $body->id = 2;
        $body->title = 'bar';
        $body->content = 'foo';
        $body->date = '2015-02-27 19:59:15';

        $this->assertInstanceOf('Fusio\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action = new SqlFetchRow();
        $action->setConnection(Environment::getService('connection'));

        $this->assertInstanceOf('Fusio\Form\Container', $action->getForm());
    }
}
