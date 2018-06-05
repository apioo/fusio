
## PHP

The PHP action processes a PHP file or inline code. There is also a complete API
[documentation](http://www.fusio-project.org/documentation/php) describing all
available objects. In the following a simple example implementation:

### Example

```php
<?php
/**
 * @var \Fusio\Engine\ConnectorInterface $connector
 * @var \Fusio\Engine\ContextInterface $context
 * @var \Fusio\Engine\RequestInterface $request
 * @var \Fusio\Engine\Response\FactoryInterface $response
 * @var \Fusio\Engine\ProcessorInterface $processor
 * @var \Fusio\Engine\ProcessorInterface $dispatcher
 * @var \Psr\Log\LoggerInterface $logger
 * @var \Psr\SimpleCache\CacheInterface $cache
 */

/** @var \Doctrine\DBAL\Connection $connection */
$connection = $connector->getConnection('My-DB');

$count  = $connection->fetchColumn('SELECT COUNT(*) FROM my_table');
$result = $connection->fetchAll('SELECT * FROM my_table ORDER BY sort DESC');

return $response->build(200, [], [
    'totalCount' => $count,
    'entries'    => $result,
]);
```
