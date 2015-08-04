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

namespace Fusio\Database;

use Fusio\Base;
use Fusio\DbTestCase;

/**
 * InstallerTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class InstallerTest extends DbTestCase
{
    /**
     * Checks whether we have an database version
     */
    public function testVersion()
    {
        $this->assertInstanceOf('Fusio\Database\VersionInterface', Installer::getVersion(Base::getVersion()), 'No database version class was provided');
    }

    /**
     * Checks whether this version is in the upgrade path
     */
    public function testUpgradePath()
    {
        $installer = new Installer($this->connection);
        $path      = $installer->getUpgradePath();

        $this->assertEquals(Base::getVersion(), current($path), 'The current version must be in the upgrade path');
    }
}
