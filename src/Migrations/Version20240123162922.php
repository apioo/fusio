<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123162922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Mapping Fusio Token with Backend Token';
    }

    public function up(Schema $schema): void
    {
        $tokenmap = $schema->createTable('dp_token_maps');
        $tokenmap->addColumn('id', 'integer', ['autoincrement' => true]);
        $tokenmap->addColumn('fusio_token', Types::TEXT);
        $tokenmap->addColumn('dp_token', Types::TEXT);
        $tokenmap->addColumn('date', 'datetime');
        $tokenmap->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('dp_token_maps');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
