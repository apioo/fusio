<?php

namespace Fusio\Backend\Api\Log;

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
 * Log
 *
 * @see http://phpsx.org/doc/design/controller.html
 */
class Collection extends SchemaApiAbstract
{
	use ProtectionTrait;

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
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Log\Collection'));

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
		$startIndex = $this->getParameter('startIndex', Validate::TYPE_INTEGER) ?: null;
		$appId      = $this->getParameter('appId', Validate::TYPE_INTEGER) ?: null;
		$routeId    = $this->getParameter('routeId', Validate::TYPE_INTEGER) ?: null;
		$search     = $this->getParameter('search', Validate::TYPE_STRING) ?: null;
		$condition  = new Condition();

		if(!empty($appId))
		{
			$condition->add('appId', '=', $appId);
		}

		if(!empty($routeId))
		{
			$condition->add('routeId', '=', $routeId);
		}

		if(!empty($search))
		{
			$condition->add('path', 'LIKE', '%' . $search . '%');
		}

		$table = $this->tableManager->getTable('Fusio\Backend\Table\Log');
		$table->setRestrictedFields(['header', 'body']);

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
}
