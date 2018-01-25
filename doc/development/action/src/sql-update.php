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

$body = $request->getBody();
$now  = new \DateTime();

if (empty($body->title)) {
    throw new StatusCode\BadRequestException('No title provided');
}

$affected = $connection->update('app_todo', [
    'title' => $body->title,
    'lastUpdated' => $now->format('Y-m-d H:i:s'),
], [
    'id' => $request->getUriFragment('id')
]);

if (empty($affected)) {
    throw new StatusCode\NotFoundException('Entry not available');
}

return $response->build(200, [], [
    'success' => true,
    'message' => 'Update successful',
]);
