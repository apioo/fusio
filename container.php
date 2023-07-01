<?php

return \PSX\Framework\Dependency\ContainerBuilder::build(
    __DIR__,
    true,
    __DIR__ . '/vendor/psx/framework/resources/container.php',
    __DIR__ . '/vendor/fusio/impl/resources/container.php',
    __DIR__ . '/resources/container.php',
    ...\Fusio\Impl\Adapter\AdapterFinder::getFiles(__DIR__ . '/provider.php')
);
