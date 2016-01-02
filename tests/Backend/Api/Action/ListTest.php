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

namespace Fusio\Impl\Backend\Api\Action;

use Fusio\Impl\Fixture;
use PSX\Test\ControllerDbTestCase;

/**
 * ListTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ListTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/action/list', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "actions": [
        {
            "name": "Cache-Response",
            "class": "Fusio\\Impl\\Action\\CacheResponse"
        },
        {
            "name": "Composite",
            "class": "Fusio\\Impl\\Action\\Composite"
        },
        {
            "name": "Condition",
            "class": "Fusio\\Impl\\Action\\Condition"
        },
        {
            "name": "HTTP-Proxy",
            "class": "Fusio\\Impl\\Action\\HttpProxy"
        },
        {
            "name": "HTTP-Request",
            "class": "Fusio\\Impl\\Action\\HttpRequest"
        },
        {
            "name": "Mongo-Delete",
            "class": "Fusio\\Impl\\Action\\MongoDelete"
        },
        {
            "name": "Mongo-Fetch-All",
            "class": "Fusio\\Impl\\Action\\MongoFetchAll"
        },
        {
            "name": "Mongo-Fetch-Row",
            "class": "Fusio\\Impl\\Action\\MongoFetchRow"
        },
        {
            "name": "Mongo-Insert",
            "class": "Fusio\\Impl\\Action\\MongoInsert"
        },
        {
            "name": "Mongo-Update",
            "class": "Fusio\\Impl\\Action\\MongoUpdate"
        },
        {
            "name": "MQ-Amqp",
            "class": "Fusio\\Impl\\Action\\MqAmqp"
        },
        {
            "name": "MQ-Beanstalk",
            "class": "Fusio\\Impl\\Action\\MqBeanstalk"
        },
        {
            "name": "Pipe",
            "class": "Fusio\\Impl\\Action\\Pipe"
        },
        {
            "name": "Processor",
            "class": "Fusio\\Impl\\Action\\Processor"
        },
        {
            "name": "SQL-Execute",
            "class": "Fusio\\Impl\\Action\\SqlExecute"
        },
        {
            "name": "SQL-Fetch-All",
            "class": "Fusio\\Impl\\Action\\SqlFetchAll"
        },
        {
            "name": "SQL-Fetch-Row",
            "class": "Fusio\\Impl\\Action\\SqlFetchRow"
        },
        {
            "name": "Static-Response",
            "class": "Fusio\\Impl\\Action\\StaticResponse"
        },
        {
            "name": "Transform",
            "class": "Fusio\\Impl\\Action\\Transform"
        },
        {
            "name": "Validator",
            "class": "Fusio\\Impl\\Action\\Validator"
        }
    ]
}
JSON;

        $this->assertEquals(null, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}
