<?php

require(__DIR__ . '/../vendor/autoload.php');

PSX\Framework\Test\Environment::setup(__DIR__, function ($fromSchema) {
    // install fusio schema
    $version = \Fusio\Impl\Database\Installer::getLatestVersion();
    $schema  = $version->getSchema();

    return $schema;
});
