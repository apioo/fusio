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

// entry point for the internal php server for testing
if (isset($_SERVER['REQUEST_URI'])) {
    $fileUris = [
        '^\/developer\/',
        '^\/documentation\/',
        '^\/fusio\/',
    ];

    foreach ($fileUris as $regexp) {
        if (preg_match('/' . $regexp . '/', $_SERVER['REQUEST_URI'])) {
            return false;
        }
    }

    // strip if the requests starts with /index.php/
    if (substr($_SERVER['REQUEST_URI'], 0, 11) == '/index.php/') {
        $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 10);
    }
}

$loader    = require(__DIR__ . '/../vendor/autoload.php');
$container = require_once(__DIR__ . '/../tests/container.php');

PSX\Framework\Bootstrap::setupEnvironment($container->get('config'));

if (isset($_SERVER['argv']) && in_array('--warmup', $_SERVER['argv'])) {
    // warmup
    $loader->addClassMap([
        'Fusio\Impl\Tests\Fixture' => __DIR__ . '/../vendor/fusio/impl/tests/Fixture.php',
        'Fusio\Impl\Tests\TestSchema' => __DIR__ . '/../vendor/fusio/impl/tests/TestSchema.php',
    ]);

    // create schema
    /** @var \Doctrine\DBAL\Connection $connection */
    $connection = $container->get('connection');
    $fromSchema = $connection->getSchemaManager()->createSchema();
    $version = \Fusio\Impl\Database\Installer::getLatestVersion();
    $toSchema = $version->getSchema();
    Fusio\Impl\Tests\TestSchema::appendSchema($toSchema);

    $queries = $fromSchema->getMigrateToSql($toSchema, $connection->getDatabasePlatform());
    foreach ($queries as $query) {
        $connection->query($query);
    }

    // insert fixtures
    $connection = new PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection($container->get('connection')->getWrappedConnection());
    PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT()->execute($connection, Fusio\Impl\Tests\Fixture::getDataSet());

    echo 'Warmup successful' . "\n";
} elseif (isset($_SERVER['argv']) && in_array('--deploy', $_SERVER['argv'])) {
    /** @var \Symfony\Component\Console\Application $console */
    $console = $container->get('console');
    /** @var \Symfony\Component\Console\Command\Command $command */
    $command = $container->get('console')->find('system:deploy');

    $input  = new \Symfony\Component\Console\Input\ArrayInput(['file' => __DIR__ . '/../.fusio.yml']);
    $output = new \Symfony\Component\Console\Output\ConsoleOutput();

    $command->run($input, $output);
} else {
    // run
    $request  = $container->get('request_factory')->createRequest();
    $response = $container->get('response_factory')->createResponse();

    $container->get('dispatch')->route($request, $response);
}
