<?php
/**
 * @var \Doctrine\DBAL\Schema\Schema $schema
 * @var \Fusio\Impl\Service\System\Migration\DBAL\QueryBucket $migration
 * @var \Doctrine\DBAL\Platforms\AbstractPlatform $platform
 */

$todoTable = $schema->createTable('app_todo');
$todoTable->addColumn('id', 'integer', ['autoincrement' => true]);
$todoTable->addColumn('status', 'integer', ['default' => 1]);
$todoTable->addColumn('title', 'string', ['length' => 64]);
$todoTable->addColumn('insertDate', 'datetime');
$todoTable->setPrimaryKey(['id']);
