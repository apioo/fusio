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

namespace App\Tests;

use Fusio\Cli\Service\Authenticator;
use Fusio\Impl\Console\Transport;
use Fusio\Impl\Framework\Loader\RoutingParser\DatabaseParser;
use PSX\Framework\Test\ControllerDbTestCase;
use PSX\Framework\Test\Environment;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * ApiTestCase
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
abstract class ApiTestCase extends ControllerDbTestCase
{
    /**
     * Contains the access token which you can use to access all endpoints of your API
     * 
     * @var string
     */
    protected $accessToken;

    public function getDataSet()
    {
        return Fixture::getFixture()->toArray();
    }

    protected function setUp(): void
    {
        parent::setUp();

        // set debug mode to false
        Environment::getService('config')->set('psx_debug', false);

        // run deploy
        /** @var Application $application */
        $application = Environment::getService('console');
        $application->setAutoExit(false);

        $this->runLogin($application);
        $this->runDeploy($application);
        $this->accessToken = $this->getAccessToken();

        // clear all cached routes after deployment since the deploy adds new
        // routes which are not in the database
        $routingParser = Environment::getService('routing_parser');
        if ($routingParser instanceof DatabaseParser) {
            $routingParser->clear();
        }

        // add scope for all new routes
        $scopeId = Fixture::getFixture()->getId('fusio_scope', 'testing');
        $routes = $this->connection->fetchAll('SELECT id FROM fusio_routes WHERE category_id = :category', ['category' => 1]);
        foreach ($routes as $route) {
            $this->connection->insert('fusio_scope_routes', [
                'scope_id' => $scopeId,
                'route_id' => $route['id'],
                'allow'    => 1,
                'methods'  => 'GET|POST|PUT|DELETE',
            ]);
        }
    }

    private function runLogin(Application $application)
    {
        $input = new ArrayInput([
            'command'    => 'login',
            '--username' => Fixture::TEST_USERNAME,
            '--password' => Fixture::TEST_PASSWORD,
        ]);

        $application->run($input, new BufferedOutput());
    }

    private function runDeploy(Application $application)
    {
        $input = new ArrayInput([
            'command' => 'deploy',
            'file'    => __DIR__ . '/../.fusio.yml',
        ]);

        $application->run($input, new BufferedOutput());
    }

    private function getAccessToken(): string
    {
        $transport = new Transport(Environment::getService('dispatch'));
        $authenticator = new Authenticator($transport);

        return $authenticator->getAccessToken();
    }
}
