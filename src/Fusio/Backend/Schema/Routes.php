<?php
/*
 * fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;

/**
 * Routes
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://phpsx.org
 */
class Routes extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('routes');
		$sb->integer('id');
		$sb->string('methods')
			->setMinLength(3)
			->setMaxLength(20);
		$sb->string('path');
		$sb->string('controller');
		$sb->arrayType('config')
			->setPrototype($this->getSchema('Fusio\Backend\Schema\Routes\Config'));

		return $sb->getProperty();
	}
}
