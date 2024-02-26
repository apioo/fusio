<?php

declare(strict_types=1);

namespace App\Migrations\System;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201227234138 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $date = new \DateTime();
        for ($i = 1; $i < 32; $i++) {
            $this->addSql('INSERT INTO app_todo (status, title, insert_date) VALUES (?, ?, ?)', [1, 'Task ' . $i, $date->format('Y-m-d H:i:s')]);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM app_todo WHERE 1=1');
    }

    /**
     * @see https://github.com/doctrine/migrations/issues/1104
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
