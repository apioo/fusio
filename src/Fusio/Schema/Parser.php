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

namespace Fusio\Schema;

use Doctrine\DBAL\Connection;
use PSX\Data\Schema\Parser\JsonSchema;
use PSX\Data\Schema\Parser\JsonSchema\RefResolver;
use PSX\File;
use PSX\Validate;

/**
 * Parser
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Parser
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Parses and resolves the json schema source and returns the object
     * presentation of the schema
     *
     * @param string $source
     */
    public function parse($source)
    {
        $resolver = new RefResolver();
        $resolver->addResolver('schema', new Resolver($this->connection));

        $parser = new JsonSchema(null, $resolver);
        $schema = $parser->parse($source);

        return serialize($schema);
    }
}
