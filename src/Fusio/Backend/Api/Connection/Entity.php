<?php

namespace Fusio\Backend\Api\Connection;

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
		$view->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Connection'));
		$view->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\Connection\Update'), $message);
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
		$connectionId = (int) $this->getUriFragment('connection_id');
		$connection   = $this->tableManager->getTable('Fusio\Backend\Table\Connection')->get($connectionId);

		if(!empty($connection))
		{
			return $connection;
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find connection');
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
		$connectionId = (int) $this->getUriFragment('connection_id');
		$connection   = $this->tableManager->getTable('Fusio\Backend\Table\Connection')->get($connectionId);

		if(!empty($connection))
		{
			$this->getValidator()->validate($record);

			$this->tableManager->getTable('Fusio\Backend\Table\Connection')->update(array(
				'id'     => $record->getId(),
				'name'   => $record->getName(),
				'class'  => $record->getClass(),
				'config' => $record->getConfig()->getRecordInfo()->getData(),
			));

			return array(
				'success' => true,
				'message' => 'Connection successful updated',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find connection');
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
		$connectionId = (int) $this->getUriFragment('connection_id');
		$connection   = $this->tableManager->getTable('Fusio\Backend\Table\Connection')->get($connectionId);

		if(!empty($connection))
		{
			$this->tableManager->getTable('Fusio\Backend\Table\Connection')->delete(array(
				'id' => $connection['id']
			));

			return array(
				'success' => true,
				'message' => 'Connection successful deleted',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find connection');
		}
	}
}
