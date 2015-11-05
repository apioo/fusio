<?php

$loader = require(__DIR__ . '/../vendor/autoload.php');
$loader->addPsr4('Fusio\\Impl\\', 'tests');

PSX\Test\Environment::setup(__DIR__ . '/..', function ($fromSchema) {

    $version = new Fusio\Impl\Database\Version\Version014();
    $schema  = $version->getSchema();
    Fusio\Impl\TestSchema::appendSchema($schema);

    return $schema;

});
