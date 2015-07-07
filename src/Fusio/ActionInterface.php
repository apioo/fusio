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

namespace Fusio;

/**
 * ActionInterface
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
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
     * @param \Fusio\Request $request
     * @param \Fusio\Parameters $configuration
     * @param \Fusio\Context $context
     * @return \Fusio\Response
     */
    public function handle(Request $request, Parameters $configuration, Context $context);
}
