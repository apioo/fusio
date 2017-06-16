
# About

The source folder contains the code which is executed if a request arrives at
an endpoint which was specified in the `.fusio.yml` deploy file. How the file
in this `src/` folder is interpreted depends on the `fusio_engine` parameter
in the `configuration.php` file. Fusio comes with three engine modes:

## PHP

```
'fusio_engine' => \Fusio\Impl\Factory\Resolver\PhpFile::class
```

With this engine PHP includes a simple PHP file. This is suitable for PHP 
developers who like a simple way to provide the endpoint logic. In the following 
an example implementation:

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

## V8

```
'fusio_engine' => \Fusio\Impl\Factory\Resolver\JavascriptFile::class,
```

With this engine you can write the endpoint logic in javascript. This is 
suitable for javascript developers who like to write the code in 
[javascript](http://www.fusio-project.org/documentation/v8). Note the v8 
implementation requires the [php v8](https://github.com/pinepain/php-v8) 
extension. In the following an example implementation:

```javascript
response.setStatusCode(200);
response.setBody({
    message: "Hello World!"
});
```

## Class

```
'fusio_engine' => \Fusio\Engine\Factory\Resolver\PhpClass::class,
```

If you want to implement the endpoint logic in a PHP class. You provide a class 
name to the action and this class gets loaded through composer on execution. The 
class must implement the `Fusio\Engine\ActionInterface`. In the following an 
example implementation:

```php
<?php

namespace App\Todo\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class AcmeAction extends ActionAbstract
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
