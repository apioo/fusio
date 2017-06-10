<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace App\Tests\Api\Todo;

use App\Tests\ApiTestCase;

/**
 * CollectionTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class CollectionTest extends ApiTestCase
{
    public function testDocumentation()
    {
        $response = $this->sendRequest('http://127.0.0.1/doc/*/todo', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "path": "\/todo",
    "version": "*",
    "status": 4,
    "description": "",
    "schema": {
        "$schema": "http:\/\/json-schema.org\/draft-04\/schema#",
        "id": "urn:schema.phpsx.org#",
        "definitions": {
            "Todo": {
                "type": "object",
                "title": "todo",
                "properties": {
                    "id": {
                        "type": "integer"
                    },
                    "status": {
                        "type": "integer"
                    },
                    "title": {
                        "type": "string",
                        "minLength": 3,
                        "maxLength": 32
                    },
                    "insertDate": {
                        "type": "string",
                        "format": "date-time"
                    }
                },
                "required": [
                    "title"
                ]
            },
            "Collection": {
                "type": "object",
                "title": "collection",
                "properties": {
                    "totalCount": {
                        "type": "integer"
                    },
                    "entry": {
                        "type": "array",
                        "items": {
                            "$ref": "#\/definitions\/Todo"
                        }
                    }
                }
            },
            "Message": {
                "type": "object",
                "title": "message",
                "properties": {
                    "success": {
                        "type": "boolean"
                    },
                    "message": {
                        "type": "string"
                    }
                }
            },
            "GET-200-response": {
                "$ref": "#\/definitions\/Collection"
            },
            "POST-request": {
                "$ref": "#\/definitions\/Todo"
            },
            "POST-200-response": {
                "$ref": "#\/definitions\/Message"
            }
        }
    },
    "methods": {
        "GET": {
            "responses": {
                "200": "#\/definitions\/GET-200-response"
            }
        },
        "POST": {
            "request": "#\/definitions\/POST-request",
            "responses": {
                "200": "#\/definitions\/POST-200-response"
            }
        }
    },
    "links": [
        {
            "rel": "swagger",
            "href": "\/export\/swagger\/*\/todo"
        },
        {
            "rel": "raml",
            "href": "\/export\/raml\/*\/todo"
        }
    ]
}
JSON;

        $this->assertEquals(null, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/todo', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "totalResults": "4",
    "entry": [
        {
            "id": "4",
            "status": "1",
            "title": "Task 4",
            "insertDate": "2016-02-17 20:15:56"
        },
        {
            "id": "3",
            "status": "1",
            "title": "Task 3",
            "insertDate": "2016-02-17 20:15:55"
        },
        {
            "id": "2",
            "status": "1",
            "title": "Task 2",
            "insertDate": "2016-02-17 20:14:49"
        },
        {
            "id": "1",
            "status": "1",
            "title": "Task 1",
            "insertDate": "2016-02-17 20:14:45"
        }
    ]
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPost()
    {
        $body     = json_encode(['title' => 'foo']);
        $response = $this->sendRequest('http://127.0.0.1/todo', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ], $body);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Insert successful"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPostInvalidPayload()
    {
        $body     = json_encode(['foo' => 'foo']);
        $response = $this->sendRequest('http://127.0.0.1/todo', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ], $body);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": false,
    "title": "Internal Server Error",
    "message": "\/ the following properties are required: title"
}
JSON;

        $this->assertEquals(500, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPostWithoutAuthorization()
    {
        $body     = json_encode(['title' => 'foo']);
        $response = $this->sendRequest('http://127.0.0.1/todo', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
        ], $body);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": false,
    "title": "Internal Server Error",
    "message": "Missing authorization header"
}
JSON;

        $this->assertEquals(401, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPut()
    {
        $response = $this->sendRequest('http://127.0.0.1/todo', 'PUT', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": false,
    "title": "Internal Server Error",
    "message": "Given request method is not supported"
}
JSON;

        $this->assertEquals(405, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testDelete()
    {
        $response = $this->sendRequest('http://127.0.0.1/todo', 'DELETE', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": false,
    "title": "Internal Server Error",
    "message": "Given request method is not supported"
}
JSON;

        $this->assertEquals(405, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }
}
