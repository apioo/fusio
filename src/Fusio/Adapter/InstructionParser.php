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

namespace Fusio\Adapter;

use PSX\Json;

/**
 * InstructionParser
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class InstructionParser
{
    public function parse($file)
    {
        $definition   = Json::decode(file_get_contents($file), false);
        $instructions = array();

        if (!empty($definition)) {
            // get action class
            if (isset($definition->actionClass) && is_array($definition->actionClass)) {
                foreach ($definition->actionClass as $actionClass) {
                    $instructions[] = new Instruction\ActionClass($actionClass);
                }
            }

            // get connection class
            if (isset($definition->connectionClass) && is_array($definition->connectionClass)) {
                foreach ($definition->connectionClass as $connectionClass) {
                    $instructions[] = new Instruction\ConnectionClass($connectionClass);
                }
            }

            // get connection
            if (isset($definition->connection) && is_array($definition->connection)) {
                foreach ($definition->connection as $connection) {
                    $instructions[] = new Instruction\Connection($connection);
                }
            }

            // get schema
            if (isset($definition->schema) && is_array($definition->schema)) {
                foreach ($definition->schema as $schema) {
                    $instructions[] = new Instruction\Schema($schema);
                }
            }

            // get action
            if (isset($definition->action) && is_array($definition->action)) {
                foreach ($definition->action as $action) {
                    $instructions[] = new Instruction\Action($action);
                }
            }

            // get routes
            if (isset($definition->routes) && is_array($definition->routes)) {
                foreach ($definition->routes as $route) {
                    $instructions[] = new Instruction\Route($route);
                }
            }
        }

        return $instructions;
    }
}
