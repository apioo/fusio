<?php

namespace Fusio\Backend\Api\Schema;

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
use PSX\Http\Exception as StatusCode;

/**
 * Collection
 *
 * @see http://phpsx.org/doc/design/controller.html
 */
class Collection extends SchemaApiAbstract
{
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
		$view = new View();
		$view->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Schema\Collection'));
		$view->setPost($this->schemaManager->getSchema('Fusio\Backend\Schema\Schema\Create'), $message);
		$view->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\Schema\Update'), $message);
		$view->setDelete($this->schemaManager->getSchema('Fusio\Backend\Schema\Schema\Delete'), $message);

		return new Documentation\Simple($view);
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

		$result      = $this->tableManager->getTable('Fusio\Backend\Table\Schema')->getAll($startIndex, null, 'id', Sql::SORT_DESC, $condition);
		$fieldsTable = $this->tableManager->getTable('Fusio\Backend\Table\Schema\Fields');

		// append the fields
		foreach($result as $key => $row)
		{
			$result[$key]['fields'] = $fieldsTable->getBySchemaId($row['id']);
		}

		return array(
			'totalItems' => $this->tableManager->getTable('Fusio\Backend\Table\Schema')->getCount($condition),
			'startIndex' => $startIndex,
			'entry'      => $result,
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

		$schemaTable = $this->tableManager->getTable('Fusio\Backend\Table\Schema');

		$schemaTable->create(array(
			'extendsId'    => $record->getExtendsId(),
			'name'         => $record->getName(),
			'propertyName' => $record->getPropertyName(),
		));

		// insert fields
		$schemaId = $schemaTable->getLastInsertId();

		$this->insertFields($schemaId, $record->getFields());

		return array(
			'success' => true,
			'message' => 'Schema successful created',
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
		$this->getValidator()->validate($record);

		$this->tableManager->getTable('Fusio\Backend\Table\Schema')->update(array(
			'id'           => $record->getId(),
			'extendsId'    => $record->getExtendsId(),
			'name'         => $record->getName(),
			'propertyName' => $record->getPropertyName(),
		));

		$this->tableManager->getTable('Fusio\Backend\Table\Schema\Fields')->deleteAllFromSchema($record->getId());

		$this->insertFields($record->getId(), $record->getFields());

		return array(
			'success' => true,
			'message' => 'Schema successful updated',
		);
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
		$this->getValidator()->validate($record);

		$this->tableManager->getTable('Fusio\Backend\Table\Schema')->delete(array(
			'id' => $record->getId(),
		));

		$this->tableManager->getTable('Fusio\Backend\Table\Schema\Fields')->deleteAllFromSchema($record->getId());

		return array(
			'success' => true,
			'message' => 'Schema successful deleted',
		);
	}

	protected function parsePattern($pattern)
	{
		set_error_handler(__CLASS__ . '::pregMatchErrorHandler');
		preg_match('/^(' . $pattern . '){1}$/', 'foobar');
		restore_error_handler();
	}

	protected function parseEnumeration($enum)
	{
		$enum   = explode(',', $enum);
		$result = array();

		foreach($enum as $value)
		{
			$value = trim($value);
			if(ctype_alnum($value))
			{
				$result[] = $value;
			}
		}

		return !empty($result) ? implode(',', $result) : null;
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

	public static function pregMatchErrorHandler($errno, $errstr)
	{
		restore_error_handler();

		throw new StatusCode\BadRequestException('Invalid regexp: ' . $errstr);
	}
}
