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

namespace Fusio\Impl\Consumer\Authorization;

use Fusio\Impl\Table\App\Token;
use Fusio\Impl\Fixture;
use PSX\Json;
use PSX\Test\ControllerDbTestCase;

/**
 * ClientCredentialsTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ClientCredentialsTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testPost()
    {
        $body     = 'grant_type=client_credentials&scope=authorization';
        $response = $this->sendRequest('http://127.0.0.1/consumer/token', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Basic ' . base64_encode('Developer:qf2vX10Ec3wFZHx0K1eL')
        ], $body);

        $body = (string) $response->getBody();
        $data = Json::decode($body);

        $this->assertEquals(200, $response->getStatusCode(), $body);

        $expireDate = strtotime('+1 hour');

        $this->arrayHasKey('access_token', $data);
        $this->arrayHasKey('token_type', $data);
        $this->assertEquals('bearer', $data['token_type']);
        $this->arrayHasKey('expires_in', $data);
        $this->assertEquals(date('Y-m-d H:i', $expireDate), date('Y-m-d H:i', $data['expires_in']));
        $this->arrayHasKey('scope', $data);
        $this->assertEquals('consumer,authorization', $data['scope']);

        // check whether the token was created
        $row = $this->connection->fetchAssoc('SELECT appId, userId, status, token, scope, expire, date FROM fusio_app_token WHERE token = :token', ['token' => $data['access_token']]);

        $this->assertEquals(2, $row['appId']);
        $this->assertEquals(4, $row['userId']);
        $this->assertEquals(Token::STATUS_ACTIVE, $row['status']);
        $this->assertEquals($data['access_token'], $row['token']);
        $this->assertEquals('consumer,authorization', $row['scope']);
        $this->assertEquals(date('Y-m-d H:i', $expireDate), date('Y-m-d H:i', strtotime($row['expire'])));
        $this->assertEquals(date('Y-m-d H:i'), substr($row['date'], 0, 16));
    }

    /**
     * As consumer we can not request an backend token
     */
    public function testPostConsumer()
    {
        $body     = 'grant_type=client_credentials&scope=authorization';
        $response = $this->sendRequest('http://127.0.0.1/consumer/token', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Basic ' . base64_encode('Consumer:qf2vX10Ec3wFZHx0K1eL')
        ], $body);

        $body = (string) $response->getBody();
        $data = Json::decode($body);

        $this->assertEquals(200, $response->getStatusCode(), $body);

        $expireDate = strtotime('+1 hour');

        $this->arrayHasKey('access_token', $data);
        $this->arrayHasKey('token_type', $data);
        $this->assertEquals('bearer', $data['token_type']);
        $this->arrayHasKey('expires_in', $data);
        $this->assertEquals(date('Y-m-d H:i', $expireDate), date('Y-m-d H:i', $data['expires_in']));
        $this->arrayHasKey('scope', $data);
        $this->assertEquals('consumer,authorization', $data['scope']);

        // check whether the token was created
        $row = $this->connection->fetchAssoc('SELECT appId, userId, status, token, scope, expire, date FROM fusio_app_token WHERE token = :token', ['token' => $data['access_token']]);

        $this->assertEquals(2, $row['appId']);
        $this->assertEquals(2, $row['userId']);
        $this->assertEquals(Token::STATUS_ACTIVE, $row['status']);
        $this->assertEquals($data['access_token'], $row['token']);
        $this->assertEquals('consumer,authorization', $row['scope']);
        $this->assertEquals(date('Y-m-d H:i', $expireDate), date('Y-m-d H:i', strtotime($row['expire'])));
        $this->assertEquals(date('Y-m-d H:i'), substr($row['date'], 0, 16));
    }

    /**
     * A deactivated user can not request a backend token
     */
    public function testPostDisabled()
    {
        $body     = 'grant_type=client_credentials&scope=authorization';
        $response = $this->sendRequest('http://127.0.0.1/consumer/token', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Basic ' . base64_encode('Disabled:qf2vX10Ec3wFZHx0K1eL')
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
