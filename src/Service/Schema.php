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

namespace Fusio\Impl\Service;

use Fusio\Engine\Schema\ParserInterface;
use Fusio\Impl\Backend\Table\Routes\Schema as TableRoutesSchema;
use Fusio\Impl\Backend\Table\Schema as TableSchema;
use PSX\Data\ResultSet;
use PSX\Data\Schema\Generator;
use PSX\Data\SchemaInterface;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;
use RuntimeException;

/**
 * Schema
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Schema
{
    protected $schemaTable;
    protected $schemaParser;

    public function __construct(TableSchema $schemaTable, TableRoutesSchema $routesSchemaTable, ParserInterface $schemaParser)
    {
        $this->schemaTable       = $schemaTable;
        $this->routesSchemaTable = $routesSchemaTable;
        $this->schemaParser      = $schemaParser;
    }

    public function getAll($startIndex = 0, $search = null, $routeId = null)
    {
        $condition = new Condition();

        if (!empty($search)) {
            $condition->like('name', '%' . $search . '%');
        }

        if (!empty($routeId)) {
            $sql = 'SELECT schemaId
                      FROM fusio_routes_schema
                     WHERE routeId = ?';

            $condition->raw('id IN (' . $sql . ')', [$routeId]);
        }

        $this->schemaTable->setRestrictedFields(['propertyName', 'source', 'cache']);

        return new ResultSet(
            $this->schemaTable->getCount($condition),
            $startIndex,
            16,
            $this->schemaTable->getAll($startIndex, 16, 'id', Sql::SORT_DESC, $condition)
        );
    }

    public function get($schemaId)
    {
        $schema = $this->schemaTable->get($schemaId);

        if (!empty($schema)) {
            return $schema;
        } else {
            throw new StatusCode\NotFoundException('Could not find schema');
        }
    }

    public function create($name, $source)
    {
        $this->schemaTable->create(array(
            'status' => TableSchema::STATUS_ACTIVE,
            'name'   => $name,
            'source' => $source,
            'cache'  => $this->schemaParser->parse($source, $name),
        ));
    }

    public function update($schemaId, $name, $source)
    {
        $schema = $this->schemaTable->get($schemaId);

        if (!empty($schema)) {
            $this->checkLocked($schema);

            $this->schemaTable->update(array(
                'id'     => $schema['id'],
                'name'   => $name,
                'source' => $source,
                'cache'  => $this->schemaParser->parse($source, $name),
            ));
        } else {
            throw new StatusCode\NotFoundException('Could not find schema');
        }
    }

    public function delete($schemaId)
    {
        $schema = $this->schemaTable->get($schemaId);

        if (!empty($schema)) {
            $this->checkLocked($schema);

            // delete route dependencies
            $this->routesSchemaTable->deleteBySchema($schema['id']);

            $this->schemaTable->delete(array(
                'id' => $schema['id']
            ));
        } else {
            throw new StatusCode\NotFoundException('Could not find schema');
        }
    }

    public function getHtmlPreview($schemaId)
    {
        $schema = $this->schemaTable->get($schemaId);

        if (!empty($schema)) {
            $generator = new Generator\Html();
            $schema    = unserialize($schema['cache']);

            if ($schema instanceof SchemaInterface) {
                return $generator->generate($schema);
            } else {
                throw new RuntimeException('Invalid schema');
            }
        } else {
            throw new StatusCode\NotFoundException('Invalid schema id');
        }
    }

    protected function checkLocked($schema)
    {
        if ($schema['status'] == TableSchema::STATUS_LOCKED) {
            $paths = $this->routesSchemaTable->getDependingRoutePaths($schema['id']);

            $paths = implode(', ', $paths);

            throw new StatusCode\ConflictException('Schema is locked because it is used by a route. Change the route status to "Development" or "Closed" to unlock the schema. The following routes reference this schema: ' . $paths);
        }
    }
}
