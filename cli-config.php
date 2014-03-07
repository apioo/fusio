<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once('vendor/autoload.php');

$container = new Fusio\Dependency\Container();
$container->setParameter('config.file', 'configuration.php');

PSX\Bootstrap::setupEnvironment($container->get('config'));

return ConsoleRunner::createHelperSet($container->get('entity_manager'));
