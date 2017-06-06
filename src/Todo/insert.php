<?php
/**
 * @var \Fusio\Engine\ConnectorInterface $connector
 * @var \Fusio\Engine\RequestInterface $request
 * @var \Fusio\Engine\Response\FactoryInterface $response
 * @var \Fusio\Engine\ProcessorInterface $processor
 * @var \Psr\Log\LoggerInterface $logger
 * @var \Psr\SimpleCache\CacheInterface $cache
 */

/** @var \Doctrine\DBAL\Connection $connection */
$connection = $connector->getConnection('Default-Connection');

$body = $request->getBody();
$now  = new \DateTime();

$connection->insert('app_todo', [
    'status' => 1,
    'title' => $body->title,
    'insertDate' => $now->format('Y-m-d H:i:s'),
]);

return $response->build(201, [], [
    'success' => true,
    'message' => 'Insert successful',
]);
