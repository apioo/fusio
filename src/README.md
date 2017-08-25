
# About

The source folder contains the action code which is executed if a request 
arrives at an endpoint which was specified in the `.fusio.yml` deploy file. 
Fusio determines the engine based on the provided action string. The following
action sources can be used:

## PHP File

```
action: "${dir.src}/Todo/collection.php"
```

If the action points to an actual file with a `php` file extension Fusio simply
includes this file. In the following an example implementation:

```php
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
```

## Javascript File

```
action: "${dir.src}/Todo/collection.js"
```

If the action points to an actual file with a `js` file extension Fusio uses
the internal v8 engine to execute the js code. This is suitable for javascript 
developers who like to write the code in [javascript](http://www.fusio-project.org/documentation/v8). 
Note the v8 implementation requires the [php v8](https://github.com/pinepain/php-v8) 
extension. In the following an example implementation:

```javascript
response.setStatusCode(200);
response.setBody({
    message: "Hello World!"
});
```

## PHP Class

```
action: "App\\Todo\\CollectionAction"
```

If the action string is an PHP class Fusio tries to autoload this class through 
composer. The class must implement the `Fusio\Engine\ActionInterface`. This is
the most advanced solution since it is also possible to access services from the
DI container. In the following an example implementation:

```php
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
```
