<?php

/*
This file returns the global DI container for the application. The DI container
which gets returned must be compatible with the symfony DI container interface.
If you want load an different configuration depending on the environment you can 
change the "config.file" parameter.
*/

$container = new Fusio\Impl\Dependency\Container();
$container->setParameter('config.file', __DIR__ . '/configuration.php');

return $container;
