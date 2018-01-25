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

use PSX\Http\Exception as StatusCode;

/** @var \Doctrine\DBAL\Connection $connection */
$connection = $connector->getConnection('Database-Connection');

$todo = $connection->fetchAssoc('SELECT * FROM app_todo WHERE id = :id', [
    'id' => $request->getUriFragment('id')
]);

if (empty($todo)) {
    throw new StatusCode\NotFoundException('Entry not available');
}

return $response->build(200, [], $todo);
