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

namespace Fusio\Impl\Backend\Schema;

use PSX\Data\Schema\Property;
use PSX\Data\SchemaAbstract;

/**
 * User
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class User extends SchemaAbstract
{
    public function getDefinition()
    {
        $sb = $this->getSchemaBuilder('user');
        $sb->integer('id');
        $sb->integer('status');
        $sb->string('name')
            ->setPattern('[A-z0-9\-\_\.]{3,32}');
        $sb->arrayType('scopes')
            ->setPrototype(Property::getString('name'));
        $sb->arrayType('apps')
            ->setPrototype($this->getSchema('Fusio\Impl\Backend\Schema\App'));
        $sb->dateTime('date');

        return $sb->getProperty();
    }
}
