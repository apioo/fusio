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

namespace Fusio\Impl\Backend\Api\Import;

use Fusio\Impl\Fixture;
use PSX\Http\Stream\StringStream;
use PSX\Test\ControllerDbTestCase;

/**
 * ProcessTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ProcessTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testPost()
    {
        $data = $this->getData();
        $body = new StringStream($data);

        $response = $this->sendRequest('http://127.0.0.1/backend/import/process', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf',
            'Content-Type'  => 'application/json',
        ), $body);


        $body = (string) $response->getBody();

        $expect = <<<'JSON'
{
    "success": true,
    "message": "Import successful"
}
JSON;

        $this->assertEquals(null, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // @TODO check entries
    }

    protected function getData()
    {
        return <<<'JSON'
{
    "routes": [
        {
            "methods": "GET|POST|PUT|DELETE",
            "path": "\/api\/pet\/:petId",
            "config": [
                {
                    "active": true,
                    "status": 4,
                    "name": "1",
                    "methods": [
                        {
                            "active": true,
                            "public": true,
                            "name": "GET",
                            "action": "${action.Welcome}",
                            "request": "${schema.Passthru}",
                            "response": "${schema.api-pet-petId-GET-response}"
                        }
                    ]
                }
            ]
        },
        {
            "methods": "GET|POST|PUT|DELETE",
            "path": "\/api\/pet",
            "config": [
                {
                    "active": true,
                    "status": 4,
                    "name": "1",
                    "methods": [
                        {
                            "active": true,
                            "public": true,
                            "name": "POST",
                            "action": "${action.Welcome}",
                            "request": "${schema.api-pet-POST-request}",
                            "response": "${schema.Passthru}"
                        },
                        {
                            "active": true,
                            "public": true,
                            "name": "PUT",
                            "action": "${action.Welcome}",
                            "request": "${schema.api-pet-PUT-request}",
                            "response": "${schema.Passthru}"
                        }
                    ]
                }
            ]
        }
    ],
    "schema": [
        {
            "name": "api-pet-petId-GET-response",
            "source": "{\n    \"type\": \"object\",\n    \"title\": \"Pet\",\n    \"properties\": {\n        \"id\": {\n            \"type\": \"integer\",\n            \"required\": true,\n            \"title\": \"id\"\n        },\n        \"category\": {\n            \"type\": \"object\",\n            \"required\": false,\n            \"title\": \"category\"\n        },\n        \"name\": {\n            \"type\": \"string\",\n            \"required\": true,\n            \"title\": \"name\"\n        },\n        \"photoUrls\": {\n            \"type\": \"array\",\n            \"required\": false,\n            \"title\": \"photoUrls\",\n            \"items\": {\n                \"type\": \"string\",\n                \"title\": \"photoUrls\"\n            },\n            \"uniqueItems\": false\n        },\n        \"tags\": {\n            \"type\": \"array\",\n            \"required\": false,\n            \"title\": \"tags\",\n            \"items\": {\n                \"type\": \"object\"\n                },\n            \"uniqueItems\": false\n        },\n        \"status\": {\n            \"type\": \"string\",\n            \"required\": false,\n            \"title\": \"status\"\n        }\n    }\n}"
        },
        {
            "name": "api-pet-POST-request",
            "source": "{\n    \"type\": \"object\",\n    \"title\": \"Pet\",\n    \"properties\": {\n        \"id\": {\n            \"type\": \"integer\",\n            \"required\": true,\n            \"title\": \"id\"\n        },\n        \"category\": {\n            \"type\": \"object\",\n            \"required\": false,\n            \"title\": \"category\"\n        },\n        \"name\": {\n            \"type\": \"string\",\n            \"required\": true,\n            \"title\": \"name\"\n        },\n        \"photoUrls\": {\n            \"type\": \"array\",\n            \"required\": false,\n            \"title\": \"photoUrls\",\n            \"items\": {\n                \"type\": \"string\",\n                \"title\": \"photoUrls\"\n            },\n            \"uniqueItems\": false\n        },\n        \"tags\": {\n            \"type\": \"array\",\n            \"required\": false,\n            \"title\": \"tags\",\n            \"items\": {\n                \"type\": \"object\"\n                },\n            \"uniqueItems\": false\n        },\n        \"status\": {\n            \"type\": \"string\",\n            \"required\": false,\n            \"title\": \"status\"\n        }\n    }\n}"
        },
        {
            "name": "api-pet-PUT-request",
            "source": "{\n    \"type\": \"object\",\n    \"title\": \"Pet\",\n    \"properties\": {\n        \"id\": {\n            \"type\": \"integer\",\n            \"required\": true,\n            \"title\": \"id\"\n        },\n        \"category\": {\n            \"type\": \"object\",\n            \"required\": false,\n            \"title\": \"category\"\n        },\n        \"name\": {\n            \"type\": \"string\",\n            \"required\": true,\n            \"title\": \"name\"\n        },\n        \"photoUrls\": {\n            \"type\": \"array\",\n            \"required\": false,\n            \"title\": \"photoUrls\",\n            \"items\": {\n                \"type\": \"string\",\n                \"title\": \"photoUrls\"\n            },\n            \"uniqueItems\": false\n        },\n        \"tags\": {\n            \"type\": \"array\",\n            \"required\": false,\n            \"title\": \"tags\",\n            \"items\": {\n                \"type\": \"object\"\n                },\n            \"uniqueItems\": false\n        },\n        \"status\": {\n            \"type\": \"string\",\n            \"required\": false,\n            \"title\": \"status\"\n        }\n    }\n}"
        }
    ]
}
JSON;
    }
}
