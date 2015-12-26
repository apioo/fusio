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

namespace Fusio\Impl\Consumer\Authorize;

use Fusio\Impl\Fixture;
use PSX\Test\ControllerDbTestCase;
use PSX\Test\Environment;

/**
 * AuthorizeTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class AuthorizeTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testPost()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'code',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'http://google.com',
            'scope' => 'bar,backend,authorization,foo',
            'allow' => true,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body);
        $code = isset($data->code) ? $data->code : null;
        $body = str_replace(trim(json_encode($code), '"'), '[token]', $body);
        $body = str_replace(urlencode($code), '[token]', $body);

        $expect = <<<JSON
{
    "code": "[token]",
    "redirectUri": "http:\/\/google.com?code=[token]"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);


        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('appId', 'userId', 'code', 'redirectUri', 'scope')
            ->from('fusio_app_code')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(3, $row['appId']);
        $this->assertEquals(1, $row['userId']);
        $this->assertEquals($code, $row['code']);
        $this->assertEquals('http://google.com', $row['redirectUri']);
        // its important that we can not obtain a backend scope
        $this->assertEquals('authorization,foo,bar', $row['scope']);
    }
}
