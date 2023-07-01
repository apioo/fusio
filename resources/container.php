<?php

use Fusio\Engine\Adapter\ServiceBuilder;
use PSX\Framework\Dependency\Configurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $services = ServiceBuilder::build($container);
    $services = Configurator::services($services);

    /*
    $services->load('App\\Action\\', __DIR__ . '/../src/Action');
    $services->load('App\\Connection\\', __DIR__ . '/../src/Connection');

    $services->load('App\\Service\\', __DIR__ . '/../src/Service')
        ->public();

    $services->load('App\\Table\\', __DIR__ . '/../src/Table')
        ->exclude('Generated')
        ->public();

    $services->load('App\\View\\', __DIR__ . '/../src/View');
    */
};
