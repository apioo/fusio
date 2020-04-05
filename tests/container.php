<?php

$container = require_once __DIR__ . '/../container.php';
$container->setParameter('config.file', __DIR__ . '/configuration.php');

return $container;
