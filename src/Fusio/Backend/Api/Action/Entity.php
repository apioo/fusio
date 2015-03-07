<?php
/*
 * Fusio
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

namespace Fusio\Backend\Api\Action;

use Fusio\Backend\Api\Authorization\ProtectionTrait;
use PSX\Api\Documentation;
use PSX\Api\Version;
use PSX\Api\View;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Http\Exception as StatusCode;
use PSX\Sql\Condition;

/**
 * Entity
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Entity extends SchemaApiAbstract
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
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Action'));
		$builder->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\Action\Update'), $message);
		$builder->setDelete(null, $message);

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
		$actionId = (int) $this->getUriFragment('action_id');
		$action   = $this->tableManager->getTable('Fusio\Backend\Table\Action')->get($actionId);

		if(!empty($action))
		{
			return $action;
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find action');
		}
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
		$actionId = (int) $this->getUriFragment('action_id');
		$action   = $this->tableManager->getTable('Fusio\Backend\Table\Action')->get($actionId);

		if(!empty($action))
		{
			$this->getValidator()->validate($record);

			$this->tableManager->getTable('Fusio\Backend\Table\Action')->update(array(
				'id'     => $record->getId(),
				'name'   => $record->getName(),
				'class'  => $record->getClass(),
				'config' => $record->getConfig()->getRecordInfo()->getData(),
			));

			return array(
				'success' => true,
				'message' => 'Action successful updated',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find action');
		}
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
		$actionId = (int) $this->getUriFragment('action_id');
		$action   = $this->tableManager->getTable('Fusio\Backend\Table\Action')->get($actionId);

		if(!empty($action))
		{
			$this->tableManager->getTable('Fusio\Backend\Table\Action')->delete(array(
				'id' => $action['id']
			));

			return array(
				'success' => true,
				'message' => 'Action successful deleted',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find action');
		}
	}
}
