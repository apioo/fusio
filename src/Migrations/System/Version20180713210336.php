<?php

namespace App\Migrations\System;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180713210336 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
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
    public function down(Schema $schema)
    {
        $schema->dropTable('app_todo');
    }
}
