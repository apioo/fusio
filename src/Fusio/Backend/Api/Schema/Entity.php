<?php

namespace Fusio\Backend\Api\Schema;

use PSX\Api\Documentation;
use PSX\Api\Version;
use PSX\Api\View;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Http\Exception as StatusCode;
use PSX\Sql\Condition;

/**
 * Schema
 *
 * @see http://phpsx.org/doc/design/controller.html
 */
class Entity extends SchemaApiAbstract
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
		$view->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Schema'));
		$view->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\Schema\Update'), $message);
		$view->setDelete(null, $message);

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
		$schemaId = (int) $this->getUriFragment('schema_id');
		$schema   = $this->tableManager->getTable('Fusio\Backend\Table\Schema')->get($schemaId);

		if(!empty($schema))
		{
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
				'id'   => $schema['id'],
				'name' => $record->getName(),
			));

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
}
