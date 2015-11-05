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

namespace Fusio\Impl\Backend\Table\Schema;

use PSX\Sql\TableAbstract;

/**
 * Validator
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Validator extends TableAbstract
{
    public function getName()
    {
        return 'fusio_schema_validator';
    }

    public function getColumns()
    {
        return array(
            'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
            'schemaId' => self::TYPE_INT,
            'ref' => self::TYPE_VARCHAR,
            'rule' => self::TYPE_VARCHAR,
            'message' => self::TYPE_VARCHAR,
        );
    }

    public function getRules($schemaId)
    {
        $sql = 'SELECT ref,
                       rule,
                       message
                  FROM fusio_schema_validator
                 WHERE schemaId = :schemaId';

        return $this->connection->fetchAll($sql, array('schemaId' => $schemaId)) ?: array();
    }

    public function removeAll($schemaId)
    {
        $this->connection->executeUpdate('DELETE FROM fusio_schema_validator WHERE schemaId = :schemaId', [
            'schemaId' => $schemaId
        ]);
    }
}
