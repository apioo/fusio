<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2017 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace App\Tests;

use Fusio\Impl\Connection\Native;
use Fusio\Impl\Migrations\DataBag;
use Fusio\Impl\Migrations\NewInstallation;

/**
 * Fixture
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Fixture
{
    public const TEST_USERNAME = 'test';
    public const TEST_PASSWORD = 'test1234';

    protected static $dataSet;

    /**
     * Appends the default Fusio system inserts, through this it is i.e.
     * possible to add test users or apps which are required for your API. The
     * test token needs the required scopes to access your endpoints
     * 
     * @return array
     * @throws \Exception
     */
    public static function append(DataBag $dataBag): void
    {
        $expire = new \DateTime();
        $expire->add(new \DateInterval('P1M'));

        $dataBag->addUser('Administrator', self::TEST_USERNAME, 'test@test.com', password_hash(self::TEST_PASSWORD, PASSWORD_DEFAULT));
        $dataBag->addScope('default', 'testing', 'Test scope');
        $dataBag->addUserScope('test', 'backend');
        $dataBag->addUserScope('test', 'consumer');
        $dataBag->addUserScope('test', 'testing');

        $inserts = self::getDemoInserts();
        foreach ($inserts as $tableName => $rows) {
            $dataBag->addTable($tableName, $rows);
        }
    }

    /**
     * Returns the demo inserts for your app specific tables. In this case we
     * simply add entries to the app_todo table
     * 
     * @return array
     * @throws \Exception
     */
    public static function getDemoInserts()
    {
        $result = [];
        $date   = new \DateTime();

        for ($i = 1; $i < 32; $i++) {
            $result[] = ['status' => 1, 'title' => 'Task ' . $i, 'insert_date' => $date->format('Y-m-d H:i:s')];
        }

        return [
            'app_todo' => $result
        ];
    }

    public static function getFixture()
    {
        if (self::$dataSet !== null) {
            return self::$dataSet;
        }

        $dataBag = NewInstallation::getData();

        // replace System connection class
        $dataBag->replace('fusio_connection', 'System', 'class', Native::class);

        self::append($dataBag);

        return self::$dataSet = $dataBag;
    }
}

