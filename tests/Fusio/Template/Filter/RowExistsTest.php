<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace Fusio\Template\Filter;

use Fusio\DbTestCase;
use PSX\Test\Environment;

/**
 * RowExistsTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class RowExistsTest extends DbTestCase
{
    public function testInvoke()
    {
        $rowExists = new RowExists();
        $rowExists->setConnection(Environment::getService('connection'));

        $this->assertEquals(1, $rowExists(1, 'fusio_action', 'id'));
    }

    /**
     * @expectedException \PSX\Http\Exception\NotFoundException
     */
    public function testInvokeInvalidValue()
    {
        $rowExists = new RowExists();
        $rowExists->setConnection(Environment::getService('connection'));

        $rowExists(8, 'fusio_action', 'id');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvokeInvalidTableName()
    {
        $rowExists = new RowExists();
        $rowExists->setConnection(Environment::getService('connection'));

        $rowExists(1, 'fusio/_action', 'id');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvokeInvalidColumnName()
    {
        $rowExists = new RowExists();
        $rowExists->setConnection(Environment::getService('connection'));

        $rowExists(1, 'fusio_action', 'i/d');
    }
}
