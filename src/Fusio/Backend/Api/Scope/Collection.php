<?php
/*
 * fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Backend\Api\Scope;

use DateTime;
use Fusio\Backend\Api\Authorization\ProtectionTrait;
use PSX\Api\Documentation;
use PSX\Api\Version;
use PSX\Api\View;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Filter as PSXFilter;
use PSX\Sql;
use PSX\Sql\Condition;
use PSX\Validate;
use PSX\Validate\Property;
use PSX\Validate\RecordValidator;
use PSX\OpenSsl;
use PSX\Util\Uuid;

/**
 * Collection
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://phpsx.org
 */
class Collection extends SchemaApiAbstract
{
	use ProtectionTrait;
	use ValidatorTrait;

	/**
	 * @Inject
	 * @var PSX\Data\Schema\SchemaManagerInterface
	 */
	protected $schemaManager;

	/**
	 * @Inject
	 * @var PSX\Sql\TableManager
	 */
	protected $tableManager;

	/**
	 * @return PSX\Api\DocumentationInterface
	 */
	public function getDocumentation()
	{
		$message = $this->schemaManager->getSchema('Fusio\Backend\Schema\Message');
		$builder = new View\Builder();
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Scope\Collection'));
		$builder->setPost($this->schemaManager->getSchema('Fusio\Backend\Schema\Scope\Create'), $message);

		return new Documentation\Simple($builder->getView());
	}

	/**
	 * Returns the GET response
	 *
	 * @param PSX\Api\Version $version
	 * @return array|PSX\Data\RecordInterface
	 */
	protected function doGet(Version $version)
	{
		$startIndex = $this->getParameter('startIndex', Validate::TYPE_INTEGER) ?: 0;
		$search     = $this->getParameter('search', Validate::TYPE_STRING) ?: null;
		$condition  = !empty($search) ? new Condition(['name', 'LIKE', '%' . $search . '%']) : null;

		$table = $this->tableManager->getTable('Fusio\Backend\Table\Scope');

		return array(
			'totalItems' => $table->getCount($condition),
			'startIndex' => $startIndex,
			'entry'      => $table->getAll($startIndex, null, 'id', Sql::SORT_DESC, $condition),
		);
	}

	/**
	 * Returns the POST response
	 *
	 * @param PSX\Data\RecordInterface $record
	 * @param PSX\Api\Version $version
	 * @return array|PSX\Data\RecordInterface
	 */
	protected function doCreate(RecordInterface $record, Version $version)
	{
		$this->getValidator()->validate($record);

		$scopeTable = $this->tableManager->getTable('Fusio\Backend\Table\Scope');

		$scopeTable->create(array(
			'name' => $record->getName(),
		));

		// insert routes
		$scopeId = $scopeTable->getLastInsertId();

		$this->insertRoutes($scopeId, $record->getRoutes());

		return array(
			'success' => true,
			'message' => 'Scope successful created',
		);
	}

	/**
	 * Returns the PUT response
	 *
	 * @param PSX\Data\RecordInterface $record
	 * @param PSX\Api\Version $version
	 * @return array|PSX\Data\RecordInterface
	 */
	protected function doUpdate(RecordInterface $record, Version $version)
	{
	}

	/**
	 * Returns the DELETE response
	 *
	 * @param PSX\Data\RecordInterface $record
	 * @param PSX\Api\Version $version
	 * @return array|PSX\Data\RecordInterface
	 */
	protected function doDelete(RecordInterface $record, Version $version)
	{
	}

	protected function insertRoutes($scopeId, $routes)
	{
		if(!empty($routes) && is_array($routes))
		{
			foreach($routes as $route)
			{
				//$this->getFieldValidator()->validate($field);

				if($route->getAllow())
				{
					$this->tableManager->getTable('Fusio\Backend\Table\Scope\Route')->create(array(
						'scopeId' => $scopeId,
						'routeId' => $route->getRouteId(),
						'allow'   => $route->getAllow() ? 1 : 0,
						'methods' => $route->getMethods(),
					));
				}
			}
		}
	}
}
