<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223083938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'DanapayAMSTables';
    }

    public function up(Schema $schema): void
    {
        $app = $schema->getTable('fusio_app');

        $company = $schema->createTable('danapay_app_companies');
        $company->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $company->addColumn('app_id', Types::INTEGER);
        $company->addColumn('company_id', Types::STRING);
        $company->addColumn('created_at', Types::DATE_IMMUTABLE);
        $company->setPrimaryKey(['id']);

        $company->addForeignKeyConstraint($app, ['app_id'], ['id']);

        $configuration = $schema->createTable('danapay_app_configurations');
        $configuration->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $configuration->addColumn('app_id', Types::INTEGER);
        $configuration->addColumn('description', Types::TEXT);
        $configuration->addColumn('redirection_url', Types::TEXT);
        $configuration->addColumn('webhook', Types::TEXT);
        $configuration->addColumn('salt', Types::STRING, ['length' => 30])->setNotnull(false);
        $configuration->addColumn('created_at', Types::DATE_IMMUTABLE);
        $configuration->addColumn('updated_at', Types::DATE_MUTABLE);
        $configuration->setPrimaryKey(['id']);

        $configuration->addForeignKeyConstraint($app, ['app_id'], ['id']);

        $operation = $schema->createTable('danapay_app_operations');
        $operation->addColumn('id', Types::STRING);
        $operation->addColumn('app_id', Types::INTEGER);
        $operation->addColumn('payment_id', Types::STRING)->setNotnull(false);
        $operation->addColumn('reference', Types::STRING)->setNotnull(false);
        $operation->addColumn('payload', Types::JSON)->setNotnull(false);
        $operation->addColumn('created_at', Types::DATE_IMMUTABLE);
        $operation->addColumn('updated_at', Types::DATE_MUTABLE);
        $operation->setPrimaryKey(['id']);

        $operation->addForeignKeyConstraint($app, ['app_id'], ['id']);
        $operation->addUniqueConstraint(['app_id', 'reference']);

        $user = $schema->createTable('danapay_app_users');
        $user->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $user->addColumn('app_id', Types::INTEGER);
        $user->addColumn('user_id', Types::STRING);
        $user->addColumn('external_user_id', Types::STRING);
        $user->addColumn('created_at', Types::DATE_IMMUTABLE);
        $user->setPrimaryKey(['id']);

        $user->addForeignKeyConstraint($app, ['app_id'], ['id']);
        $user->addUniqueConstraint(['app_id', 'user_id']);

        $callback = $schema->createTable('danapay_callbacks');
        $callback->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $callback->addColumn('app_id', Types::INTEGER);
        $callback->addColumn('url', Types::TEXT);
        $callback->addColumn('request', Types::TEXT);
        $callback->addColumn('response', Types::TEXT);
        $callback->addColumn('insert_date', Types::DATE_IMMUTABLE);
        $callback->setPrimaryKey(['id']);

        $callback->addForeignKeyConstraint($app, ['app_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('danapay_app_companies');
        $schema->dropTable('danapay_app_configurations');
        $schema->dropTable('danapay_app_operations');
        $schema->dropTable('danapay_app_users');
        $schema->dropTable('danapay_callbacks');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
