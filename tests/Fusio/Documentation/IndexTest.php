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

namespace Fusio\Documentation;

use Fusio\Fixture;
use PSX\Test\ControllerDbTestCase;
use PSX\Test\Environment;

/**
 * IndexTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class IndexTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/doc', 'GET', array(
            'User-Agent' => 'Fusio TestCase',
        ));

        $body   = (string) $response->getBody();
        $expect = <<<JSON
{
    "routings": [
        {
            "path": "\/backend\/action",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/action\/:action_id",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/app",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/app\/:app_id",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/connection",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/connection\/:connection_id",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/log",
            "methods": [
                "GET"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/log\/:log_id",
            "methods": [
                "GET"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/routes",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/routes\/:route_id",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/schema",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/schema\/:schema_id",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/scope",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/scope\/:scope_id",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/user",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/user\/:user_id",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/backend\/account\/change_password",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        },
        {
            "path": "\/foo",
            "methods": [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ],
            "version": "*"
        }
    ],
    "links": [
        {
            "rel": "self",
            "href": "http:\/\/127.0.0.1\/doc"
        },
        {
            "rel": "detail",
            "href": "http:\/\/127.0.0.1\/doc\/{version}\/{path}"
        }
    ]
}
JSON;

        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}
