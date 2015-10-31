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

namespace Fusio\Backend\Api\Schema;

use Fusio\Authorization\ProtectionTrait;
use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Filter as PSXFilter;
use PSX\Http\Exception as StatusCode;
use PSX\Loader\Context;
use PSX\Sql;
use PSX\Sql\Condition;
use PSX\Validate;

/**
 * Collection
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Collection extends SchemaApiAbstract
{
    use ProtectionTrait;
    use ValidatorTrait;

    /**
     * @Inject
     * @var \PSX\Data\Schema\SchemaManagerInterface
     */
    protected $schemaManager;

    /**
     * @Inject
     * @var \Fusio\Schema\Parser
     */
    protected $schemaParser;

    /**
     * @Inject
     * @var \PSX\Sql\TableManager
     */
    protected $tableManager;

    /**
     * @return \PSX\Api\DocumentationInterface
     */
    public function getDocumentation()
    {
        $resource = new Resource(Resource::STATUS_ACTIVE, $this->context->get(Context::KEY_PATH));

        $resource->addMethod(Resource\Factory::getMethod('GET')
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Backend\Schema\Schema\Collection'))
        );

        $resource->addMethod(Resource\Factory::getMethod('POST')
            ->setRequest($this->schemaManager->getSchema('Fusio\Backend\Schema\Schema\Create'))
            ->addResponse(201, $this->schemaManager->getSchema('Fusio\Backend\Schema\Message'))
        );

        return new Documentation\Simple($resource);
    }

    /**
     * Returns the GET response
     *
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doGet(Version $version)
    {
        $startIndex = $this->getParameter('startIndex', Validate::TYPE_INTEGER) ?: 0;
        $search     = $this->getParameter('search', Validate::TYPE_STRING) ?: null;
        $condition  = !empty($search) ? new Condition(['name', 'LIKE', '%' . $search . '%']) : null;

        $table = $this->tableManager->getTable('Fusio\Backend\Table\Schema');
        $table->setRestrictedFields(['propertyName', 'source', 'cache']);

        return array(
            'totalItems' => $table->getCount($condition),
            'startIndex' => $startIndex,
            'entry'      => $table->getAll($startIndex, null, 'id', Sql::SORT_DESC, $condition),
        );
    }

    /**
     * Returns the POST response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doCreate(RecordInterface $record, Version $version)
    {
        $schemaTable = $this->tableManager->getTable('Fusio\Backend\Table\Schema');
        $schemaTable->create(array(
            'name'   => $record->getName(),
            'source' => $record->getSource(),
            'cache'  => $this->schemaParser->parse($record->getSource(), $record->getName()),
        ));

        // handle validators
        $schemaId   = $schemaTable->getLastInsertId();
        $validators = $record->getValidators();
        if (!empty($validators)) {
            foreach ($validators as $validator) {
                $this->tableManager->getTable('Fusio\Backend\Table\Schema\Validator')->create([
                    'schemaId' => $schemaId,
                    'ref'      => $validator['ref'],
                    'rule'     => $validator['rule'],
                    'message'  => $validator['message'],
                ]);
            }
        }

        return array(
            'success' => true,
            'message' => 'Schema successful created',
        );
    }

    /**
     * Returns the PUT response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doUpdate(RecordInterface $record, Version $version)
    {
    }

    /**
     * Returns the DELETE response
     *
     * @param \PSX\Data\RecordInterface $record
     * @param \PSX\Api\Version $version
     * @return array|\PSX\Data\RecordInterface
     */
    protected function doDelete(RecordInterface $record, Version $version)
    {
    }
}
