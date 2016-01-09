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

    public function testGet()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }

    public function testPost()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(500, $response->getStatusCode(), $body);
        $this->assertEquals('/responseType is required', substr($data['message'], 0, 25), $body);
    }

    public function testPostCode()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'code',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'http://google.com',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => true,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertArrayHasKey('type', $data, $body);
        $this->assertArrayHasKey('code', $data, $body);
        $this->assertArrayHasKey('redirectUri', $data, $body);
        $this->assertEquals('code', $data['type'], $body);
        $this->assertNotEmpty($data['code'], $body);
        $this->assertEquals('http://google.com?code=' . urlencode($data['code']) . '&state=state', $data['redirectUri'], $body);

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
        $this->assertEquals($data['code'], $row['code']);
        $this->assertEquals('http://google.com', $row['redirectUri']);
        // its important that we can not obtain a backend scope
        $this->assertEquals('authorization,foo,bar', $row['scope']);
    }

    public function testPostCodeWithoutRedirectUri()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'code',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => true,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertArrayHasKey('type', $data, $body);
        $this->assertArrayHasKey('code', $data, $body);
        $this->assertArrayHasKey('redirectUri', $data, $body);
        $this->assertEquals('code', $data['type'], $body);
        $this->assertNotEmpty($data['code'], $body);
        $this->assertEquals('#', $data['redirectUri'], $body);

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
        $this->assertEquals($data['code'], $row['code']);
        $this->assertNull($row['redirectUri']);
        // its important that we can not obtain a backend scope
        $this->assertEquals('authorization,foo,bar', $row['scope']);
    }

    public function testPostCodeDisallow()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'code',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'http://google.com',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => false,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertArrayHasKey('type', $data, $body);
        $this->assertArrayHasKey('redirectUri', $data, $body);
        $this->assertEquals('access_denied', $data['type'], $body);
        $this->assertEquals('http://google.com?error=access_denied&state=state', $data['redirectUri'], $body);
    }

    public function testPostToken()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'token',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'http://google.com',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => true,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertArrayHasKey('type', $data, $body);
        $this->assertArrayHasKey('token', $data, $body);
        $this->assertArrayHasKey('redirectUri', $data, $body);
        $this->assertEquals('token', $data['type'], $body);
        $this->assertTrue(is_array($data['token']), $body);
        $this->assertNotEmpty($data['token']['access_token'], $body);
        $this->assertEquals('bearer', $data['token']['token_type'], $body);
        $this->assertEquals(strtotime('+1 hour'), $data['token']['expires_in'], $body);
        $this->assertEquals('authorization,foo,bar', $data['token']['scope'], $body);

        // add state parameter which should be added by the server if available
        $data['token']['state'] = 'state';

        $this->assertEquals('http://google.com#' . http_build_query($data['token']), $data['redirectUri'], $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('appId', 'userId', 'status', 'token', 'scope', 'expire')
            ->from('fusio_app_token')
            ->where('token = :token')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql, [
            'token' => $data['token']['access_token']
        ]);

        $this->assertEquals(3, $row['appId']);
        $this->assertEquals(1, $row['userId']);
        $this->assertEquals(1, $row['status']);
        $this->assertEquals($data['token']['access_token'], $row['token']);
        $this->assertEquals('authorization,foo,bar', $row['scope']);
        $this->assertEquals(date('Y-m-d H:i:s', strtotime('+1 hour')), $row['expire']);
    }

    public function testPostTokenWithoutRedirectUri()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'token',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => false,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertArrayHasKey('type', $data, $body);
        $this->assertArrayHasKey('redirectUri', $data, $body);
        $this->assertEquals('access_denied', $data['type'], $body);
        $this->assertEquals('#', $data['redirectUri'], $body);
    }

    public function testPostTokenDisallow()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'token',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'http://google.com',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => false,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertArrayHasKey('type', $data, $body);
        $this->assertArrayHasKey('redirectUri', $data, $body);
        $this->assertEquals('access_denied', $data['type'], $body);
        $this->assertEquals('http://google.com#error=access_denied&state=state', $data['redirectUri'], $body);
    }

    public function testPostInvalidResponseType()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'foo',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'http://google.com',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => false,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(400, $response->getStatusCode(), $body);
        $this->assertEquals('Invalid response type', substr($data['message'], 0, 21), $body);
    }

    public function testPostInvalidClient()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'code',
            'clientId' => 'a347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'http://google.com',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => false,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(400, $response->getStatusCode(), $body);
        $this->assertEquals('Unknown client id', substr($data['message'], 0, 17), $body);
    }

    public function testPostInvalidRedirectUri()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'code',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'foo',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => false,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(400, $response->getStatusCode(), $body);
        $this->assertEquals('Redirect uri must be an absolute url', substr($data['message'], 0, 36), $body);
    }

    public function testPostInvalidScheme()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'code',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'foo://google.com',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => false,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(400, $response->getStatusCode(), $body);
        $this->assertEquals('Invalid redirect uri scheme', substr($data['message'], 0, 27), $body);
    }

    public function testPostInvalidHost()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'responseType' => 'code',
            'clientId' => '5347307d-d801-4075-9aaa-a21a29a448c5',
            'redirectUri' => 'http://yahoo.com',
            'scope' => 'bar,backend,authorization,foo',
            'state' => 'state',
            'allow' => false,
        ]));

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(400, $response->getStatusCode(), $body);
        $this->assertEquals('Redirect uri must have the same host as the app url', substr($data['message'], 0, 51), $body);
    }

    public function testPut()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'PUT', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }

    public function testDelete()
    {
        $response = $this->sendRequest('http://127.0.0.1/consumer/authorize', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer b8f6f61bd22b440a3e4be2b7491066682bfcde611dbefa1b15d2e7f6522d77e2'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }
}
