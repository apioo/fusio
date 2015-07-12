<?php

$loader = require(__DIR__ . '/../vendor/autoload.php');
$loader->add('Fusio', 'tests');

PSX\Test\Environment::setup(__DIR__ . '/..', function($fromSchema){

    $version = new Fusio\Database\Version\Version010();
	$schema  = $version->getSchema();
    Fusio\TestSchema::appendSchema($schema);

    return $schema;

});

