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

namespace Fusio\Custom\Tests;

use PSX\Json\Parser;

/**
 * ApiTestCase
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
abstract class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string $body
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function send($method, $uri, $body = null, array $headers = [])
    {
        $options = [
            'http_errors' => false,
        ];

        if (!empty($headers)) {
            $options['headers'] = $headers;
        }

        if ($body !== null) {
            if (is_array($body) || $body instanceof \stdClass) {
                $options['json'] = $body;
            } else {
                $options['body'] = $body;
            }
        }

        return self::getClient()->request($method, $uri, $options);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string $body
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendAuthorized($method, $uri, $body = null, array $headers = [])
    {
        $headers['Authorization'] = 'Bearer ' . self::getAccessToken();

        return $this->send($method, $uri, $body, $headers);
    }

    /**
     * @return string
     */
    private function getAccessToken()
    {
        static $accessToken;

        if ($accessToken) {
            return $accessToken;
        }

        $response = $this->send('POST', 'consumer/login', [
            'username' => 'Developer',
            'password' => 'qf2vX10Ec3wFZHx0K1eL',
        ]);

        $body = (string) $response->getBody();
        $data = Parser::decode($body);

        if (isset($data->token)) {
            return $accessToken = $data->token;
        } else {
            $this->fail('Could not request access token');
        }
    }

    private function getBaseUri()
    {
        $config = require __DIR__ . '/configuration.php';

        if (is_array($config) && isset($config['psx_url'])) {
            return $config['psx_url'];
        } else {
            $this->markTestSkipped('Could not determine base uri');
        }
    }

    /**
     * @return \GuzzleHttp\Client
     */
    private function getClient()
    {
        static $client;

        if ($client) {
            return $client;
        }

        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->getBaseUri(),
        ]);

        // check whether the base uri is available
        $client->get('/');

        // create a scope which contains the routes from the deployment so that
        // we can access the protected endpoints
        $this->assignScopeToDeveloper();

        return $client;
    }
    
    private function assignScopeToDeveloper()
    {
        // get backend access token
        $response = $this->send('POST', 'backend/token', 'grant_type=client_credentials', [
            'Authorization' => 'Basic ' . base64_encode('Developer:qf2vX10Ec3wFZHx0K1eL')
        ]);

        $body  = (string) $response->getBody();
        $data  = Parser::decode($body);
        $token = isset($data->access_token) ? $data->access_token : null;

        // check whether scope is already available
        $response = $this->send('GET', 'backend/scope?search=todo', null, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $body = (string) $response->getBody();
        $data = Parser::decode($body);

        if ($data->totalResults !== 1) {
            // create scope
            $data = [
                'name' => 'todo',
                'description' => 'Todo scope',
                'routes' => [[
                    'routeId' => 68,
                    'allow'   => true,
                    'methods' => 'GET|POST|PUT|DELETE',
                ], [
                    'routeId' => 67,
                    'allow'   => true,
                    'methods' => 'GET|POST|PUT|DELETE',
                ]],
            ];

            $response = $this->send('POST', 'backend/scope', Parser::encode($data), [
                'Authorization' => 'Bearer ' . $token
            ]);

            $body = (string) $response->getBody();
            $data = Parser::decode($body);

            if ($data->success !== true) {
                $this->fail('Could not create scope');
            }
        }

        // assign scope to user developer
        $data = [
            'name'   => 'Developer',
            'email'  => 'developer@localhost.com',
            'scopes' => ['todo', 'authorization', 'consumer', 'backend'],
        ];

        $response = $this->send('PUT', 'backend/user/4', Parser::encode($data), [
            'Authorization' => 'Bearer ' . $token
        ]);

        $body = (string) $response->getBody();
        $data = Parser::decode($body);

        if ($data->success !== true) {
            $this->fail('Could not assign scope');
        }
    }
}
