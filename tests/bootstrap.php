<?php

$loader = require(__DIR__ . '/../vendor/autoload.php');
$loader->add('Fusio', 'tests');

PSX\Test\Environment::setup(__DIR__ . '/..', function($fromSchema){

	$schema = Fusio\Schema::getSchema();
    Fusio\TestSchema::appendSchema($schema);

    return $schema;

});

