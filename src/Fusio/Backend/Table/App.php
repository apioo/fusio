<?php
/*
 * Fusio
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

namespace Fusio\Backend\Table;

use PSX\Sql\TableAbstract;

/**
 * App
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class App extends TableAbstract
{
	public function getName()
	{
		return 'fusio_app';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'userId' => self::TYPE_INT,
			'status' => self::TYPE_INT,
			'name' => self::TYPE_VARCHAR,
			'url' => self::TYPE_VARCHAR,
			'appKey' => self::TYPE_VARCHAR,
			'appSecret' => self::TYPE_VARCHAR,
			'date' => self::TYPE_DATETIME,
		);
	}
}
