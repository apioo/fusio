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

namespace Fusio\Impl\Schema;

use PSX\Validate\ValidatorInterface;

/**
 * Validator
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Validator
{
    protected $connection;
    protected $schemaId;

    public function __construct(Connection $connection, $schemaId)
    {
        $this->connection = $connection;
        $this->schemaId   = $schemaId;
    }

    public function validate($data)
    {
        $validator = $this->getValidator();
        if ($validator instanceof ValidatorInterface) {
            $validator->validate($data);
        }
    }

    protected function getValidator()
    {
        $result = $this->connection->fetchAll('SELECT ref, rule, message FROM fusio_schema_validator WHERE schemaId = :schemaId', [
            'schemaId' => $this->schemaId,
        ]);

        $fields = [];
        $validator = new RecordValidator();

        foreach ($result as $row) {

            $fields[] = new Property('path', Validate::TYPE_STRING, array(new Filter\Path())),
        }

        new RecordValidator(new Validate(), array(
            new Property('/id', Validate::TYPE_INTEGER, array(new PSXFilter\PrimaryKey($this->tableManager->getTable('Fusio\Impl\Backend\Table\Routes')))),
            new Property('/foo/bar', Validate::TYPE_STRING, array(new Filter\Methods())),
            new Property('/path', Validate::TYPE_STRING, array(new Filter\Path())),
            new Property('/config', Validate::TYPE_ARRAY),
        ));
    }
}
