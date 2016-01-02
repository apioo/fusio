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

namespace Fusio\Impl\Schema;

use Doctrine\DBAL\Connection;
use Fusio\Engine\Schema\ParserInterface;
use PSX\Data\Schema\Parser\JsonSchema;
use PSX\Data\Schema\Parser\JsonSchema\RefResolver;
use PSX\Data\Schema\Property;
use PSX\File;
use PSX\Validate;
use RuntimeException;

/**
 * Parser
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Parser implements ParserInterface
{
    protected $connection;

    public function __construct(Connection $connection = null)
    {
        $this->connection = $connection;
    }

    /**
     * Parses and resolves the json schema source and returns the object
     * presentation of the schema
     *
     * @param string $source
     * @return string
     */
    public function parse($source)
    {
        $resolver = new RefResolver();

        if ($this->connection !== null) {
            $resolver->addResolver('schema', new Resolver($this->connection));
        }

        $parser = new JsonSchema(null, $resolver);
        $schema = $parser->parse($source);

        if (!$schema->getDefinition() instanceof Property\ComplexType) {
            throw new RuntimeException('Schema must be an object');
        }

        return serialize($schema);
    }
}
