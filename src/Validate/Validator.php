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

namespace Fusio\Impl\Validate;

use Doctrine\DBAL\Connection;
use Fusio\Impl\Connector;
use PSX\Cache;
use PSX\Validate;
use PSX\Validate\Property;
use PSX\Validate\ValidatorAbstract;

/**
 * Validator
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Validator extends ValidatorAbstract
{
    protected $connection;
    protected $schemaId;
    protected $container;
    protected $cache;
    protected $fields;

    public function __construct(Connection $connection, $schemaId, ServiceContainer $container, Cache $cache)
    {
        parent::__construct(new Validate());

        $this->connection = $connection;
        $this->schemaId   = $schemaId;
        $this->container  = $container;
        $this->cache      = $cache;
        $this->fields     = $this->fetchFields();
    }

    protected function fetchFields()
    {
        $fields = array();
        $result = $this->connection->fetchAll('SELECT ref, rule, message FROM fusio_schema_validator WHERE schemaId = :schemaId', [
            'schemaId' => $this->schemaId,
        ]);

        foreach ($result as $row) {
            $fields[] = new Property(
                $row['ref'],
                null,
                [new ExpressionFilter($this->container, $this->cache, $row['rule'], $row['message'])],
                false
            );
        }

        return $fields;
    }
}
