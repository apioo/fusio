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
        $expect = <<<JSON
{
    "method": [
        "POST"
    ],
    "path": "\/foo",
    "versions": [
        {
            "version": 1,
            "status": 4
        }
    ],
    "see_others": {},
    "resource": {
        "version": 1,
        "status": 4,
        "data": {
            "Schema": "<div class=\"psx-resource psx-api-resource-generator-html-schema\" data-status=\"4\" data-path=\"\/foo\"><h4>Schema<\/h4><div class=\"psx-resource-method\" data-method=\"POST\"><div class=\"psx-resource-data psx-resource-request\"><h5>POST Request<\/h5><div class=\"psx-resource-data-content\"><div id=\"psx-type-1f48a85d4ba4eea96e191b48f552281e\" class=\"psx-complex-type\"><h1>test<\/h1><div class=\"psx-type-description\"><\/div><table class=\"table psx-type-properties\"><colgroup><col width=\"20%\" \/><col width=\"20%\" \/><col width=\"40%\" \/><col width=\"20%\" \/><\/colgroup><thead><tr><th>Property<\/th><th>Type<\/th><th>Description<\/th><th>Constraints<\/th><\/tr><\/thead><tbody><tr><td><span class=\"psx-property-name psx-property-optional\">title<\/span><\/td><td><span class=\"psx-property-type psx-property-type-string\">String<\/span><\/td><td><span class=\"psx-property-description\"><\/span><\/td><td><\/td><\/tr><tr><td><span class=\"psx-property-name psx-property-optional\">content<\/span><\/td><td><span class=\"psx-property-type psx-property-type-string\">String<\/span><\/td><td><span class=\"psx-property-description\"><\/span><\/td><td><\/td><\/tr><tr><td><span class=\"psx-property-name psx-property-optional\">date<\/span><\/td><td><span class=\"psx-property-type psx-property-type-datetime\"><a href=\"http:\/\/tools.ietf.org\/html\/rfc3339#section-5.6\" title=\"RFC3339\">DateTime<\/a><\/span><\/td><td><span class=\"psx-property-description\"><\/span><\/td><td><\/td><\/tr><\/tbody><\/table><\/div><\/div><\/div><div class=\"psx-resource-data psx-resource-response\"><h5>POST Response - 200 OK<\/h5><div class=\"psx-resource-data-content\"><div id=\"psx-type-aeadc8d940a294aeacf0ab2d3e7e4e4b\" class=\"psx-complex-type\"><h1>passthru<\/h1><div class=\"psx-type-description\">No schema was specified all data will pass thru. Please contact the API provider for more informations about the data format.<\/div><\/div><\/div><\/div><\/div><\/div>"
        }
    }
}
JSON;

        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}
