<?php

namespace Fusio\Backend\Api\Routes;

use PSX\Api\Documentation;
use PSX\Api\Version;
use PSX\Api\View;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Filter as PSXFilter;
use PSX\Sql\Condition;
use PSX\Validate;
use PSX\Validate\Property;
use PSX\Validate\RecordValidator;

/**
 * Routes
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
		$view->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Routes\Collection'));
		$view->setPost($this->schemaManager->getSchema('Fusio\Backend\Schema\Routes\Create'), $message);
		$view->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\Routes\Update'), $message);
		$view->setDelete($this->schemaManager->getSchema('Fusio\Backend\Schema\Routes\Delete'), $message);

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
		$condition  = !empty($search) ? new Condition(['path', 'LIKE', '%' . $search . '%']) : null;

		return array(
			'totalItems' => $this->tableManager->getTable('Fusio\Backend\Table\Routes')->getCount($condition),
			'startIndex' => $startIndex,
			'entry'      => $this->tableManager->getTable('Fusio\Backend\Table\Routes')->getAll($startIndex, null, null, null, $condition),
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

		$this->tableManager->getTable('Fusio\Backend\Table\Routes')->create(array(
			'methods'    => $record->getMethods(),
			'path'       => $record->getPath(),
			'controller' => $record->getController(),
		));

		return array(
			'success' => true,
			'message' => 'Route successful created',
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

		$this->tableManager->getTable('Fusio\Backend\Table\Routes')->update(array(
			'id'         => $record->getId(),
			'methods'    => $record->getMethods(),
			'path'       => $record->getPath(),
			'controller' => $record->getController(),
		));

		return array(
			'success' => true,
			'message' => 'Route successful updated',
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

		$this->tableManager->getTable('Fusio\Backend\Table\Routes')->delete(array(
			'id' => $record->getId(),
		));

		return array(
			'success' => true,
			'message' => 'Route successful deleted',
		);
	}
}
