<?php

$loader = require(__DIR__ . '/../vendor/autoload.php');
$loader->add('Fusio', 'tests');

PSX\Test\Environment::setup(__DIR__ . '/..', function($fromSchema){

	return Fusio\Schema::getSchema();

});

