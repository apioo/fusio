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

/**
 * PrepareTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class PrepareTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $prepare = new Prepare();

        $this->assertEquals('?', $prepare('foo'));
        $this->assertEquals(['foo'], $prepare->getParameters());
    }

    public function testInvokeSerialize()
    {
        $prepare = new Prepare();

        $this->assertEquals('?', $prepare(['foo' => 'bar']));
        $this->assertEquals(['a:1:{s:3:"foo";s:3:"bar";}'], $prepare->getParameters());
    }

    public function testClear()
    {
        $prepare = new Prepare();
        $prepare('foo');

        $this->assertEquals(1, count($prepare->getParameters()));

        $prepare->clear();

        $this->assertEquals(0, count($prepare->getParameters()));
    }
}
