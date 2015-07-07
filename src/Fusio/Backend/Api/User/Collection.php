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

namespace Fusio\Backend\Api\User;

use DateTime;
use Fusio\Authorization\ProtectionTrait;
use PSX\Api\Documentation;
use PSX\Api\Resource;
use PSX\Api\Version;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Filter as PSXFilter;
use PSX\Loader\Context;
use PSX\OpenSsl;
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
            ->addResponse(200, $this->schemaManager->getSchema('Fusio\Backend\Schema\User\Collection'))
        );

        $resource->addMethod(Resource\Factory::getMethod('POST')
            ->setRequest($this->schemaManager->getSchema('Fusio\Backend\Schema\User\Create'))
            ->addResponse(201, $this->schemaManager->getSchema('Fusio\Backend\Schema\User\Message'))
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

        $result = $this->tableManager->getTable('Fusio\Backend\Table\User')->getAll($startIndex, null, 'id', Sql::SORT_DESC, $condition);

        return array(
            'totalItems' => $this->tableManager->getTable('Fusio\Backend\Table\User')->getCount($condition),
            'startIndex' => $startIndex,
            'entry'      => $result,
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
        $this->getValidator()->validate($record);

        $password = sha1(mcrypt_create_iv(40));

        $table = $this->tableManager->getTable('Fusio\Backend\Table\User');
        $table->create(array(
            'status'   => $record->getStatus(),
            'name'     => $record->getName(),
            'password' => \password_hash($password, PASSWORD_DEFAULT),
            'date'     => new DateTime(),
        ));

        $this->insertScopes($table->getLastInsertId(), $record->getScopes());

        return array(
            'success'  => true,
            'message'  => 'User successful created',
            'password' => $password,
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

    protected function insertScopes($userId, $scopes)
    {
        if (!empty($scopes) && is_array($scopes)) {
            $scopeTable = $this->tableManager->getTable('Fusio\Backend\Table\User\Scope');
            $scopes     = $this->tableManager
                ->getTable('Fusio\Backend\Table\Scope')
                ->getByNames($scopes);

            foreach ($scopes as $scope) {
                $scopeTable->create(array(
                    'userId'  => $userId,
                    'scopeId' => $scope['id'],
                ));
            }
        }
    }
}
