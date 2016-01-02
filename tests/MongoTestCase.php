<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Impl;

use MongoConnectionException;
use MongoDB;
use PSX\Test\Environment;

/**
 * MongoTestCase
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class MongoTestCase extends DbTestCase
{
    protected static $hasConnection = true;

    protected $mongodb;
    protected $collection;

    protected function setUp()
    {
        if (!class_exists('MongoDB')) {
            $this->markTestSkipped('MongoDB extension not available');
        }

        if (!self::$hasConnection) {
            $this->markTestSkipped('MongoDB connection not available');
        }

        parent::setUp();

        try {
            $this->mongodb = Environment::getService('connector')->getConnection(3);
        } catch (MongoConnectionException $e) {
            self::$hasConnection = false;

            $this->markTestSkipped('MongoDB connection not available');
        }

        $this->collection = $this->mongodb->createCollection('app_news');

        $table   = $this->getDataSet()->getTable('app_news');
        $columns = $table->getTableMetaData()->getColumns();

        for ($i = 0; $i < $table->getRowCount(); $i++) {
            $row = array();
            foreach ($columns as $name) {
                $row[$name] = $table->getValue($i, $name);
            }

            $this->collection->insert($row);
        }
    }

    protected function tearDown()
    {
        parent::tearDown();

        if (self::$hasConnection) {
            $this->mongodb->dropCollection('app_news');
        }
    }
}
