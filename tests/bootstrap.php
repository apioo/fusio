<?php

require(__DIR__ . '/../vendor/autoload.php');

PSX\Framework\Test\Environment::setup(__DIR__);

runMigrations();

function runMigrations()
{
    /** @var \Doctrine\DBAL\Connection $connection */
    $connection = \PSX\Framework\Test\Environment::getService('connection');

    // run Fusio migrations
    $configuration = \Fusio\Impl\Migrations\ConfigurationBuilder::fromSystem(
        \PSX\Framework\Test\Environment::getService('connection')
    );

    $factory = new \Doctrine\Migrations\DependencyFactory($configuration);
    $factory->getMigrator()->migrate();

    // replace System connection class
    $connection->update('fusio_connection', ['class' => \Fusio\Impl\Connection\Native::class], ['id' => 1]);

    $connection->getConfiguration()->setFilterSchemaAssetsExpression("~^(?!fusio_)~");

    // run App migrations
    $configuration = \Fusio\Impl\Migrations\ConfigurationBuilder::fromConnector(
        'System',
        \PSX\Framework\Test\Environment::getService('connector')
    );

    $factory = new \Doctrine\Migrations\DependencyFactory($configuration);
    $factory->getMigrator()->migrate();

    $connection->getConfiguration()->setFilterSchemaAssetsExpression(null);
}
