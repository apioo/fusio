<?php

namespace App\Migrations\System;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180713210701 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $date = new \DateTime();
        for ($i = 1; $i < 32; $i++) {
            $this->addSql('INSERT INTO app_todo (status, title, insert_date) VALUES (?, ?, ?)', [1, 'Task ' . $i, $date->format('Y-m-d H:i:s')]);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM app_todo WHERE 1=1');
    }
}
