<?php

declare(strict_types=1);

namespace App\Migrations\System;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230908115221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Webhook Logs';
    }

    public function up(Schema $schema) : void
    {
        $danapay_callbacks = $schema->createTable('danapay_callbacks');
        $danapay_callbacks->addColumn('id', 'integer', ['autoincrement' => true]);
        $danapay_callbacks->addColumn('app_id', 'integer');
        $danapay_callbacks->addColumn('url', 'text');
        $danapay_callbacks->addColumn('request', 'text');
        $danapay_callbacks->addColumn('response', 'text');
        $danapay_callbacks->addColumn('insert_date', 'datetime');
        $danapay_callbacks->setPrimaryKey(['id']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('danapay_callbacks');
    }
}
