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
use PSX\Cache;
use PSX\Cache\Handler\Memory;
use PSX\Test\Environment;

/**
 * CacheResponseTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
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
