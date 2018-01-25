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

/** @var \MongoDB\Database $connection */
$connection = $connector->getConnection('Mongodb-Connection');
$collection = $connection->selectCollection('app_todo');

$collection->insertOne($request->getBody());

return $response->build(201, [], [
    'success' => true,
    'message' => 'Insert successful',
]);
