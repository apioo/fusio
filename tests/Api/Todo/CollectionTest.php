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

namespace Fusio\Custom\Tests\Api\Todo;

use Fusio\Custom\Tests\ApiTestCase;
use PSX\Framework\Test\Environment;

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
        $response = $this->send('GET', 'doc/*/todo');

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
            "href": "\/index.php\/export\/swagger\/*\/todo"
        },
        {
            "rel": "raml",
            "href": "\/index.php\/export\/raml\/*\/todo"
        }
    ]
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testGet()
    {
        $response = $this->send('GET', 'todo');

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "totalResults": "4",
    "entry": [
        {
            "id": "3",
            "status": "1",
            "title": "Task 3",
            "insertDate": "2016-02-17 20:15:56"
        },
        {
            "id": "4",
            "status": "1",
            "title": "Task 4",
            "insertDate": "2016-02-17 20:15:56"
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
        $response = $this->sendAuthorized('POST', 'todo', ['title' => 'foo']);

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
        $response = $this->sendAuthorized('POST', 'todo', ['foo' => 'bar']);

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
        $response = $this->send('POST', 'todo');

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
        $response = $this->send('PUT', 'todo');

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
        $response = $this->send('DELETE', 'todo');

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
