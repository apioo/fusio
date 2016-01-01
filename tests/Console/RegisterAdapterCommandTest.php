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

namespace Fusio\Impl\Console;

use Fusio\Impl\Fixture;
use PSX\Test\ControllerDbTestCase;
use PSX\Test\Environment;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * RegisterAdapterCommandTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class RegisterAdapterCommandTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testCommand()
    {
        $command = Environment::getService('console')->find('register');

        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream('y' . "\n" . '/import' . "\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'class'   => 'Fusio\Impl\Adapter\TestAdapter',
        ]);

        $display = $commandTester->getDisplay();

        $this->assertRegExp('/Registration successful/', $display, $display);

        // check action class
        $actionId = $this->connection->fetchColumn('SELECT id FROM fusio_action_class WHERE class = :class', [
            'class' => 'Fusio\Impl\Adapter\Test\VoidAction',
        ]);

        $this->assertEquals(21, $actionId);

        // check connection class
        $connectionId = $this->connection->fetchColumn('SELECT id FROM fusio_connection_class WHERE class = :class', [
            'class' => 'Fusio\Impl\Adapter\Test\VoidConnection',
        ]);

        $this->assertEquals(7, $connectionId);

        // check connection
        $connection = $this->connection->fetchAssoc('SELECT id, class, config FROM fusio_connection WHERE name = :name', [
            'name' => 'Adapter-Connection',
        ]);

        $this->assertEquals(4, $connection['id']);
        $this->assertEquals('Fusio\Impl\Adapter\Test\VoidConnection', $connection['class']);
        $this->assertEquals(['foo' => 'bar'], unserialize($connection['config']));

        // check schema
        $schema = $this->connection->fetchAssoc('SELECT id, propertyName, source, cache FROM fusio_schema WHERE name = :name', [
            'name' => 'Adapter-Schema',
        ]);

        $source = <<<JSON
{
    "id": "http://fusio-project.org",
    "title": "process",
    "type": "object",
    "properties": {
        "logId": {
            "type": "integer"
        },
        "title": {
            "type": "string"
        },
        "content": {
            "type": "string"
        }
    }
}
JSON;

        $this->assertEquals(3, $schema['id']);
        $this->assertEquals(null, $schema['propertyName']);
        $this->assertJsonStringEqualsJsonString($source, $schema['source']);
        $this->assertInstanceOf('PSX\Data\Schema', unserialize($schema['cache']));

        // check action
        $action = $this->connection->fetchAssoc('SELECT id, class, config FROM fusio_action WHERE name = :name', [
            'name' => 'Void-Action',
        ]);

        $this->assertEquals(4, $action['id']);
        $this->assertEquals('Fusio\Impl\Adapter\Test\VoidAction', $action['class']);
        $this->assertEquals(['foo' => 'bar', 'connection' => '4'], unserialize($action['config']));

        // check routes
        $route = $this->connection->fetchAssoc('SELECT id, status, methods, controller, config FROM fusio_routes WHERE path = :path', [
            'path' => '/import/void',
        ]);

        $this->assertEquals(51, $route['id']);
        $this->assertEquals(1, $route['status']);
        $this->assertEquals('GET|POST|PUT|DELETE', $route['methods']);
        $this->assertEquals('Fusio\Impl\Controller\SchemaApiController', $route['controller']);

        $versions = unserialize($route['config']);
        $data     = array();

        foreach ($versions as $config) {
            // transforms the complete object structure to an array
            $data[] = json_decode(json_encode($config), true);
        }

        $this->assertEquals([[
            'active' => true,
            'status' => 4,
            'name' => '1',
            'methods' => [[
                'active' => true,
                'public' => true,
                'name' => 'GET',
                'action' => 4,
                'request' => 3,
                'response' => 1,
            ]]
        ]], $data);
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}
