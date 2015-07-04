<?php

namespace Fusio;

use Doctrine\DBAL\Schema\Schema as DbSchema;

class TestSchema
{
    public static function appendSchema(DbSchema $schema)
    {
        $actionTable = $schema->createTable('app_news');
        $actionTable->addColumn('id', 'integer', array('autoincrement' => true));
        $actionTable->addColumn('title', 'string', array('length' => 64));
        $actionTable->addColumn('content', 'string', array('length' => 255));
        $actionTable->addColumn('date', 'datetime');
        $actionTable->setPrimaryKey(array('id'));
    }
}
