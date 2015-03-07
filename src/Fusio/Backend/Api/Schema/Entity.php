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

namespace Fusio\Backend\Api\Schema;

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
 * @link    http://phpsx.org
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
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Schema'));
		$builder->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\Schema\Update'), $message);
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
		$schemaId = (int) $this->getUriFragment('schema_id');
		$schema   = $this->tableManager->getTable('Fusio\Backend\Table\Schema')->get($schemaId);

		if(!empty($schema))
		{
			// append the fields
			$schema['fields'] = $this->tableManager
				->getTable('Fusio\Backend\Table\Schema\Fields')
				->getBySchemaId($schema['id']);

			return $schema;
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find schema');
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
		$schemaId = (int) $this->getUriFragment('schema_id');
		$schema   = $this->tableManager->getTable('Fusio\Backend\Table\Schema')->get($schemaId);

		if(!empty($schema))
		{
			$this->getValidator()->validate($record);

			$this->tableManager->getTable('Fusio\Backend\Table\Schema')->update(array(
				'id'           => $schema['id'],
				'extendsId'    => $record->getExtendsId(),
				'name'         => $record->getName(),
				'propertyName' => $record->getPropertyName(),
			));

			$this->tableManager->getTable('Fusio\Backend\Table\Schema\Fields')->deleteAllFromSchema($schema['id']);

			$this->insertFields($schema['id'], $record->getFields());

			return array(
				'success' => true,
				'message' => 'Schema successful updated',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find schema');
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
		$schemaId = (int) $this->getUriFragment('schema_id');
		$schema   = $this->tableManager->getTable('Fusio\Backend\Table\Schema')->get($schemaId);

		if(!empty($schema))
		{
			$this->tableManager->getTable('Fusio\Backend\Table\Schema')->delete(array(
				'id' => $schema['id']
			));

			$this->tableManager->getTable('Fusio\Backend\Table\Schema\Fields')->deleteAllFromSchema($schema['id']);

			return array(
				'success' => true,
				'message' => 'Schema successful deleted',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find schema');
		}
	}

	protected function insertFields($schemaId, $fields)
	{
		if(!empty($fields) && is_array($fields))
		{
			foreach($fields as $field)
			{
				//$this->getFieldValidator()->validate($field);

				$constraint  = $field->getConstraint();
				$min         = null;
				$max         = null;
				$pattern     = null;
				$enumeration = null;

				if(!empty($constraint))
				{
					if(preg_match('/^(\d+),\s?(\d+)$/', $constraint, $matches))
					{
						$min = (int) $matches[1];
						$max = (int) $matches[2];
					}
					else if(substr($constraint, 0, 2) == 'P=')
					{
						$pattern = $this->parsePattern(substr($constraint, 2));
					}
					else if(substr($constraint, 0, 2) == 'E=')
					{
						$enumeration = $this->parseEnumeration(substr($constraint, 2));
					}
					else
					{
						throw new StatusCode\BadRequestException('Invalid constraint must be i.e.: "0,9", "P=[A-z]+" or "E=foo,bar,baz"');
					}
				}

				$this->tableManager->getTable('Fusio\Backend\Table\Schema\Fields')->create(array(
					'schemaId'    => $schemaId,
					'refId'       => $field->getRefId(),
					'name'        => $field->getName(),
					'type'        => $field->getType(),
					'required'    => $field->getRequired(),
					'min'         => $min,
					'max'         => $max,
					'pattern'     => $pattern,
					'enumeration' => $enumeration,
				));
			}
		}
	}
}
