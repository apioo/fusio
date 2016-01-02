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

use PSX\Data\SchemaAbstract;

/**
 * Log
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Log extends SchemaAbstract
{
    public function getDefinition()
    {
        $sb = $this->getSchemaBuilder('log');
        $sb->integer('id');
        $sb->string('ip');
        $sb->string('userAgent');
        $sb->string('method');
        $sb->string('path');
        $sb->string('header');
        $sb->string('body');
        $sb->dateTime('date');
        $sb->arrayType('errors')
            ->setPrototype($this->getSchema('Fusio\Impl\Backend\Schema\Log\Error'));

        return $sb->getProperty();
    }
}
