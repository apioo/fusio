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
        $response = $this->sendRequest('/doc/*/todo', 'GET', [
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
            "GET-500-response": {
                "$ref": "#\/definitions\/Message"
            },
            "POST-request": {
                "$ref": "#\/definitions\/Todo"
            },
            "POST-201-response": {
                "$ref": "#\/definitions\/Message"
            },
            "POST-500-response": {
                "$ref": "#\/definitions\/Message"
            }
        }
    },
    "methods": {
        "GET": {
            "responses": {
                "200": "#\/definitions\/GET-200-response",
                "500": "#\/definitions\/GET-500-response"
            }
        },
        "POST": {
            "request": "#\/definitions\/POST-request",
            "responses": {
                "201": "#\/definitions\/POST-201-response",
                "500": "#\/definitions\/POST-500-response"
            }
        }
    },
    "links": [
        {
            "rel": "openapi",
            "href": "\/index.php\/export\/openapi\/*\/todo"
        },
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

        $this->assertEquals(null, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testGet()
    {
        $response = $this->sendRequest('/todo', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $actual = preg_replace('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', '0000-00-00 00:00:00', $actual);
        $expect = <<<'JSON'
{
    "totalResults": "31",
    "entry": [
        {
            "id": "1",
            "status": "1",
            "title": "Task 1",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "2",
            "status": "1",
            "title": "Task 2",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "3",
            "status": "1",
            "title": "Task 3",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "4",
            "status": "1",
            "title": "Task 4",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "5",
            "status": "1",
            "title": "Task 5",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "6",
            "status": "1",
            "title": "Task 6",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "7",
            "status": "1",
            "title": "Task 7",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "8",
            "status": "1",
            "title": "Task 8",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "9",
            "status": "1",
            "title": "Task 9",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "10",
            "status": "1",
            "title": "Task 10",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "11",
            "status": "1",
            "title": "Task 11",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "12",
            "status": "1",
            "title": "Task 12",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "13",
            "status": "1",
            "title": "Task 13",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "14",
            "status": "1",
            "title": "Task 14",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "15",
            "status": "1",
            "title": "Task 15",
            "insertDate": "0000-00-00 00:00:00"
        },
        {
            "id": "16",
            "status": "1",
            "title": "Task 16",
            "insertDate": "0000-00-00 00:00:00"
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
        $response = $this->sendRequest('/todo', 'POST', [
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

        $this->assertEquals(201, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = Environment::getService('connector')->getConnection('Default-Connection');
        $actual = $connection->fetchAssoc('SELECT id, status, title FROM app_todo ORDER BY id DESC LIMIT 1');
        $expect = [
            'id' => 32,
            'status' => 1,
            'title' => 'foo',
        ];

        $this->assertEquals($expect, $actual);
    }

    public function testPostInvalidPayload()
    {
        $body     = json_encode(['foo' => 'foo']);
        $response = $this->sendRequest('/todo', 'POST', [
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
        $response = $this->sendRequest('/todo', 'POST', [
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
        $response = $this->sendRequest('/todo', 'PUT', [
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
        $response = $this->sendRequest('/todo', 'DELETE', [
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
