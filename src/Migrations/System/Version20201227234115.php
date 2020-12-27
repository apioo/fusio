<?php

declare(strict_types=1);

namespace App\Migrations\System;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201227234115 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $todoTable = $schema->createTable('app_todo');
        $todoTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $todoTable->addColumn('status', 'integer', ['default' => 1]);
        $todoTable->addColumn('title', 'string', ['length' => 64]);
        $todoTable->addColumn('insert_date', 'datetime');
        $todoTable->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('app_todo');
    }

    /**
     * @see https://github.com/doctrine/migrations/issues/1104
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
