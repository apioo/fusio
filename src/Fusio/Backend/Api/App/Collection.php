<?php

namespace Fusio\Backend\Api\App;

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
 * App
 *
 * @see http://phpsx.org/doc/design/controller.html
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
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\App\Collection'));
		$builder->setPost($this->schemaManager->getSchema('Fusio\Backend\Schema\App\Create'), $message);
		$builder->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\App\Update'), $message);
		$builder->setDelete($this->schemaManager->getSchema('Fusio\Backend\Schema\App\Delete'), $message);

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

		$table = $this->tableManager->getTable('Fusio\Backend\Table\App');
		$table->setRestrictedFields(['url', 'appSecret']);

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

		$appKey    = Uuid::pseudoRandom();
		$appSecret = hash('sha256', OpenSsl::randomPseudoBytes(256));

		$this->tableManager->getTable('Fusio\Backend\Table\App')->create(array(
			'status'    => $record->getStatus(),
			'name'      => $record->getName(),
			'url'       => $record->getUrl(),
			'appKey'    => $appKey,
			'appSecret' => $appSecret,
			'date'      => new DateTime(),
		));

		return array(
			'success' => true,
			'message' => 'App successful created',
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

		$this->tableManager->getTable('Fusio\Backend\Table\App')->update(array(
			'id'     => $record->getId(),
			'status' => $record->getStatus(),
			'name'   => $record->getName(),
			'url'    => $record->getUrl(),
		));

		return array(
			'success' => true,
			'message' => 'App successful updated',
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

		$this->tableManager->getTable('Fusio\Backend\Table\App')->delete(array(
			'id' => $record->getId(),
		));

		return array(
			'success' => true,
			'message' => 'App successful deleted',
		);
	}
}
