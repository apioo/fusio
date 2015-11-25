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

namespace Fusio\Impl\Backend\Api\Action;

use Fusio\Impl\Fixture;
use PSX\Test\ControllerDbTestCase;

/**
 * FormTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class FormTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/backend/action/form?class=' . urlencode('Fusio\\Impl\\Action\\SqlFetchRow'), 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "element": [
        {
            "element": "http:\/\/fusio-project.org\/ns\/2015\/form\/select",
            "options": [
                {
                    "key": 2,
                    "value": "DBAL"
                },
                {
                    "key": 3,
                    "value": "MongoDB"
                },
                {
                    "key": 1,
                    "value": "Native-Connection"
                }
            ],
            "name": "connection",
            "title": "Connection"
        },
        {
            "element": "http:\/\/fusio-project.org\/ns\/2015\/form\/textarea",
            "mode": "sql",
            "name": "sql",
            "title": "SQL",
            "help": "The SELECT statment which gets executed. It is possible to access values from the environment with i.e. <code ng-non-bindable>{{ request.uriFragment(\"news_id\")|prepare }}<\/code>. <b>Note you must use the prepare filter for each parameter in order to generate a safe SQL query which uses prepared statments.<\/b> Click <a ng-click=\"help.showDialog('help\/template.md')\">here<\/a> for more informations about the template syntax."
        }
    ]
}
JSON;

        $this->assertEquals(null, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}
