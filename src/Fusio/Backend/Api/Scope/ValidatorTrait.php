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

namespace Fusio\Backend\Api\Scope;

use Fusio\Backend\Filter\Scope as Filter;
use PSX\Filter as PSXFilter;
use PSX\Validate;
use PSX\Validate\Property;
use PSX\Validate\RecordValidator;

/**
 * ValidatorTrait
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
trait ValidatorTrait
{
	protected function getValidator()
	{
		return new RecordValidator(new Validate(), array(
			new Property('id', Validate::TYPE_INTEGER, array(new PSXFilter\PrimaryKey($this->tableManager->getTable('Fusio\Backend\Table\Scope')))),
			new Property('name', Validate::TYPE_STRING),
			new Property('routes', Validate::TYPE_ARRAY),
		));
	}
}
