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

namespace Fusio\Adapter\Instruction;

use Fusio\Adapter\InstructionAbstract;

/**
 * Schema
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Schema extends InstructionAbstract
{
    public function getName()
    {
        return 'Schema';
    }

    public function getDescription()
    {
        return isset($this->payload->name) ? $this->payload->name : null;
    }

    public function getPayload()
    {
        // in case the source is not a string the schema was embedded into the
        // adapter specification so transform the schema to json
        if (isset($this->payload->source) && !is_string($this->payload->source)) {
            $this->payload->source = json_encode($this->payload->source, JSON_PRETTY_PRINT);
        }

        return $this->payload;
    }
}
