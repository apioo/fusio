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

namespace Fusio\Impl\Documentation;

use Fusio\Impl\Fixture;
use PSX\Test\ControllerDbTestCase;

/**
 * DetailTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class DetailTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/doc/*/foo', 'GET', array(
            'User-Agent' => 'Fusio TestCase',
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "path": "\/foo",
    "version": 1,
    "status": 4,
    "schema": {
        "$schema": "http:\/\/json-schema.org\/draft-04\/schema#",
        "id": "urn:schema.phpsx.org#",
        "type": "object",
        "definitions": {
            "ref1f48a85d4ba4eea96e191b48f552281e": {
                "type": "object",
                "title": "test",
                "properties": {
                    "title": {
                        "type": "string"
                    },
                    "content": {
                        "type": "string"
                    },
                    "date": {
                        "type": "string"
                    }
                },
                "additionalProperties": false
            },
            "POST-request": {
                "$ref": "#\/definitions\/ref1f48a85d4ba4eea96e191b48f552281e"
            }
        }
    },
    "versions": [
        {
            "version": 1,
            "status": 4
        }
    ],
    "methods": {
        "POST": {
            "request": "#\/definitions\/POST-request",
            "responses": {
                "200": "#\/definitions\/POST-200-response"
            }
        }
    },
    "links": [
        {
            "rel": "wsdl",
            "href": "\/export\/wsdl\/1\/foo"
        },
        {
            "rel": "swagger",
            "href": "\/export\/swagger\/1\/foo"
        },
        {
            "rel": "raml",
            "href": "\/export\/raml\/1\/foo"
        }
    ]
}
JSON;

        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}
