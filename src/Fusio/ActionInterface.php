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

namespace Fusio;

use PSX\Data\RecordInterface;

/**
 * ActionInterface
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://phpsx.org
 */
interface ActionInterface extends ConfigurableInterface
{
	/**
	 * An action executes a specific action and returns an array as result. This
	 * could be i.e. an select on an table which returns the result as array or
	 * an push of the incomming message to an mq. On an GET request the $data
	 * is always an empty record. All actions must be placed in the Action 
	 * folder
	 *
	 * @param Fusio\Parameters $parameters
	 * @param Fusio\Body $data
	 * @param Fusio\Parameters $configuration
	 * @return array
	 */
	public function handle(Parameters $parameters, Body $data, Parameters $configuration);
}
