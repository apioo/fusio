<?php

use Doctrine\DBAL\Schema\AbstractAsset;

require(__DIR__ . '/../vendor/autoload.php');

PSX\Framework\Test\Environment::setup(__DIR__);

runMigrations();

function runMigrations()
{
    /** @var \Doctrine\DBAL\Connection $connection */
    $connection = \PSX\Framework\Test\Environment::getService('connection');

    // run Fusio migrations
    $configuration = \Fusio\Impl\Migrations\ConfigurationBuilder::fromSystem($connection);

    $factory = new \Doctrine\Migrations\DependencyFactory($configuration);
    $factory->getMigrator()->migrate();

    // replace System connection class
    $connection->update('fusio_connection', ['class' => \Fusio\Impl\Connection\Native::class], ['id' => 1]);

    $connection->getConfiguration()->setSchemaAssetsFilter(static function($assetName) {
        if ($assetName instanceof AbstractAsset) {
            $assetName = $assetName->getName();
        }
        return preg_match('~^(?!fusio_)~', $assetName);
    });

    // run App migrations
    $configuration = \Fusio\Impl\Migrations\ConfigurationBuilder::fromConnector(
        'System',
        \PSX\Framework\Test\Environment::getService('connector')
    );

    $factory = new \Doctrine\Migrations\DependencyFactory($configuration);
    $factory->getMigrator()->migrate();

    $connection->getConfiguration()->setSchemaAssetsFilter(null);
}
