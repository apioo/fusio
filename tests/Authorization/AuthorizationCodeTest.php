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

namespace Fusio\Impl\Authorization;

use Fusio\Impl\Backend\Table\App\Token;
use Fusio\Impl\Fixture;
use PSX\Test\ControllerDbTestCase;
use PSX\Json;

/**
 * AuthorizationCodeTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class AuthorizationCodeTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testPost()
    {
        $body     = 'grant_type=authorization_code&code=GHMbtJi0ZuAUnp80';
        $response = $this->sendRequest('http://127.0.0.1/authorization/token', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Basic ' . base64_encode('5347307d-d801-4075-9aaa-a21a29a448c5:342cefac55939b31cd0a26733f9a4f061c0829ed87dae7caff50feaa55aff23d')
        ], $body);

        $body = (string) $response->getBody();
        $data = Json::decode($body);

        $this->assertEquals(200, $response->getStatusCode(), $body);

        $expireDate = strtotime('+2 day');

        $this->arrayHasKey('access_token', $data);
        $this->arrayHasKey('token_type', $data);
        $this->assertEquals('bearer', $data['token_type']);
        $this->arrayHasKey('expires_in', $data);
        $this->assertEquals(date('Y-m-d H:i', $expireDate), date('Y-m-d H:i', $data['expires_in']));
        $this->arrayHasKey('scope', $data);
        $this->assertEquals('authorization', $data['scope']);

        // check whether the token was created
        $row = $this->connection->fetchAssoc('SELECT appId, userId, status, token, scope, expire, date FROM fusio_app_token WHERE token = :token', ['token' => $data['access_token']]);

        $this->assertEquals(3, $row['appId']);
        $this->assertEquals(2, $row['userId']);
        $this->assertEquals(Token::STATUS_ACTIVE, $row['status']);
        $this->assertEquals($data['access_token'], $row['token']);
        $this->assertEquals('authorization', $row['scope']);
        $this->assertEquals(date('Y-m-d H:i', $expireDate), date('Y-m-d H:i', strtotime($row['expire'])));
        $this->assertEquals(date('Y-m-d H:i'), substr($row['date'], 0, 16));
    }

    /**
     * A pending app can not request an API token
     */
    public function testPostPending()
    {
        $body     = 'grant_type=authorization_code&code=GHMbtJi0ZuAUnp80';
        $response = $this->sendRequest('http://127.0.0.1/authorization/token', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Basic ' . base64_encode('7c14809c-544b-43bd-9002-23e1c2de6067:bb0574181eb4a1326374779fe33e90e2c427f28ab0fc1ffd168bfd5309ee7caa')
        ], $body);

        $body = (string) $response->getBody();
        
        $expect = <<<JSON
{
    "error": "server_error",
    "error_description": "Unknown user"
}
JSON;

        $this->assertEquals(400, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    /**
     * A pending app can not request an API token
     */
    public function testPostDeactivated()
    {
        $body     = 'grant_type=authorization_code&code=GHMbtJi0ZuAUnp80';
        $response = $this->sendRequest('http://127.0.0.1/authorization/token', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Basic ' . base64_encode('f46af464-f7eb-4d04-8661-13063a30826b:17b882987298831a3af9c852f9cd0219d349ba61fcf3fc655ac0f07eece951f9')
        ], $body);

        $body = (string) $response->getBody();
        
        $expect = <<<JSON
{
    "error": "server_error",
    "error_description": "Unknown user"
}
JSON;

        $this->assertEquals(400, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }
}
