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
    protected static $dataSet;

    /**
     * Returns the default Fusio system inserts, through this it is i.e.
     * possible to add test users or apps which are required for your API. The
     * test token needs the required scopes to access your endpoints
     * 
     * @return array
     * @throws \Exception
     */
    public static function getSystemInserts()
    {
        $expire = new \DateTime();
        $expire->add(new \DateInterval('P1M'));

        $scopes = ['todo'];

        return [
            'fusio_scope' => [
                ['name' => 'testing', 'description' => 'Test scope'],
            ],
            'fusio_app_scope' => [
                ['app_id' => 1, 'scope_id' => 4],
            ],
            'fusio_app_token' => [
                ['app_id' => 1, 'user_id' => 1, 'status' => 1, 'token' => 'da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf', 'scope' => implode(',', $scopes), 'ip' => '127.0.0.1', 'expire' => $expire->format('Y-m-d H:i:s'), 'date' => '2015-06-25 22:49:09'],
            ],
            'fusio_user_scope' => [
                ['user_id' => 1, 'scope_id' => 4],
            ]
        ];
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

        $installInserts = NewInstallation::getData();

        // replace System connection class
        $installInserts['fusio_connection'][0]['class'] = Native::class;

        $dataSet = array_merge_recursive(
            $installInserts,
            self::getSystemInserts(),
            self::getDemoInserts()
        );

        return self::$dataSet = $dataSet;
    }
}

