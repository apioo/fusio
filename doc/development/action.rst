
Action
======

The ``src/`` folder contains the action code which is executed if a request 
arrives at an endpoint which was specified in the ``.fusio.yml`` deploy file. 
Fusio determines the engine based on the provided action string. The following
engines are available:

Engines
-------

PHP File
^^^^^^^^

.. code-block:: yaml

    action: "${dir.src}/Todo/collection.php"

If the action points to a file with a ``php`` file extension Fusio simply 
includes this file. In the following an example implementation:

.. code-block:: php

    <?php
    /**
     * @var \Fusio\Engine\ConnectorInterface $connector
     * @var \Fusio\Engine\ContextInterface $context
     * @var \Fusio\Engine\RequestInterface $request
     * @var \Fusio\Engine\Response\FactoryInterface $response
     * @var \Fusio\Engine\ProcessorInterface $processor
     * @var \Psr\Log\LoggerInterface $logger
     * @var \Psr\SimpleCache\CacheInterface $cache
     */
    
    // @TODO handle request and return response
    
    $response->build(200, [], [
        'message' => 'Hello World!',
    ]);

HTTP Url
^^^^^^^^

.. code-block:: yaml

    action: "http://foo.bar"

If the action contains an ``http`` or ``https`` url the request gets forwarded
to the defined endpoint. Fusio automatically adds some additional headers to
the request which may be used by the endpoint i.e.:

.. code-block:: http

    X-Fusio-Route-Id: 72
    X-Fusio-User-Anonymous: 1
    X-Fusio-User-Id: 4
    X-Fusio-App-Id: 3
    X-Fusio-App-Key: 1ba7b2e5-fa1a-4153-8668-8a855902edda
    X-Fusio-Remote-Ip: 127.0.0.1

Static file
^^^^^^^^^^^

.. code-block:: yaml

    action: "${dir.src}/static.json"

If the action points to a simple file Fusio will simply forward the content to
the client. This is helpful if you want to build fast an sample API with dummy 
responses.

PHP Class
^^^^^^^^^

.. code-block:: yaml

    action: "App\\Todo\\CollectionAction"

If the action string is a PHP class Fusio tries to autoload this class through 
composer. The class must implement the ``Fusio\Engine\ActionInterface``. This is
the most advanced solution since it is also possible to access services from the
DI container. In the following an example implementation:

.. code-block:: php

    <?php
    
    namespace App\Todo;
    
    use Fusio\Engine\ActionAbstract;
    use Fusio\Engine\ContextInterface;
    use Fusio\Engine\ParametersInterface;
    use Fusio\Engine\RequestInterface;
    
    class CollectionAction extends ActionAbstract
    {
        public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
        {
            // @TODO handle request and return response
    
            return $this->response->build(200, [], [
                'message' => 'Hello World!',
            ]);
        }
    }

Examples
--------

Please take a look at the `recipes`_ section of our website, there we have
example code to complete a specific task i.e. send a HTTP request or query data
from a database.

.. _recipes: https://www.fusio-project.org/documentation/recipes
.. _javascript: https://www.fusio-project.org/documentation/v8
.. _php-v8: https://github.com/pinepain/php-v8
