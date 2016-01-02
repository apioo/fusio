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
use PSX\Data\Schema\Parser\JsonSchema\Document;
use PSX\Data\Schema\Parser\JsonSchema\RefResolver;
use PSX\Data\Schema\Parser\JsonSchema\ResolverInterface;
use PSX\Json;
use PSX\Uri;
use RuntimeException;

/**
 * Resolver
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Resolver implements ResolverInterface
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function resolve(Uri $uri, Document $source, RefResolver $resolver)
    {
        $name = ltrim($uri->getPath(), '/');
        $row  = $this->connection->fetchAssoc('SELECT name, source FROM fusio_schema WHERE name LIKE :name', array('name' => $name));

        if (!empty($row)) {
            $data = Json::decode($row['source']);

            if (is_array($data)) {
                $document = new Document($data, $resolver, null, $uri);

                return $document;
            } else {
                throw new RuntimeException(sprintf('Schema %s must be an object', $row['name']));
            }
        } else {
            throw new RuntimeException('Invalid schema reference ' . $name);
        }
    }
}
