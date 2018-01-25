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

/** @var \Doctrine\DBAL\Connection $connection */
$connection = $connector->getConnection('Database-Connection');

$count   = $connection->fetchColumn('SELECT COUNT(*) FROM app_todo');
$entries = $connection->fetchAll('SELECT * FROM app_todo WHERE status = 1 ORDER BY insertDate DESC LIMIT 16');

return $response->build(200, [], [
    'totalResults' => $count,
    'entry' => $entries,
]);
