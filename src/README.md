
# Development

The `src/` folder contains the action code which is executed if a request 
arrives at an endpoint which was specified in the `.fusio.yml` deploy file. 
Fusio determines the engine based on the provided action string. The following
engines are available. Please take a look at the `doc/action/` folder to see
example action implementations.

## PHP File

```yaml
action: "${dir.src}/Todo/collection.php"
```

If the action points to a file with a `php` file extension Fusio simply includes 
this file. In the following an example implementation:

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

```yaml
action: "${dir.src}/Todo/collection.js"
```

If the action points to a file with a `js` file extension Fusio uses the 
internal v8 engine to execute the js code. This is suitable for javascript 
developers who like to write the code in [javascript](http://www.fusio-project.org/documentation/v8). 
Note the v8 implementation requires the [php v8](https://github.com/pinepain/php-v8) 
extension. In the following an example implementation:

```javascript
response.setStatusCode(200);
response.setBody({
    message: "Hello World!"
});
```

## HTTP Url

```yaml
action: "http://foo.bar"
```

If the action contains an `http` or `https` url the request gets forwarded
to the defined endpoint. Fusio automatically adds some additional headers to
the request which may be used by the endpoint i.e.:

```http
X-Fusio-Route-Id: 72
X-Fusio-User-Anonymous: 1
X-Fusio-User-Id: 4
X-Fusio-App-Id: 3
X-Fusio-App-Key: 1ba7b2e5-fa1a-4153-8668-8a855902edda
X-Fusio-Remote-Ip: 127.0.0.1
```

## Static file

```yaml
action: "${dir.src}/static.json"
```

If the action points to a simple file Fusio will simply forward the content to
the client. This is helpful if you want to build fast an sample API with dummy 
responses.

## PHP Class

```yaml
action: "App\\Todo\\CollectionAction"
```

If the action string is a PHP class Fusio tries to autoload this class through 
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

