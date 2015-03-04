<?php

namespace Fusio\Backend\Api\User;

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
 * User
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
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\User\Collection'));
		$builder->setPost($this->schemaManager->getSchema('Fusio\Backend\Schema\User\Create'), $message);
		$builder->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\User\Update'), $message);
		$builder->setDelete($this->schemaManager->getSchema('Fusio\Backend\Schema\User\Delete'), $message);

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
	 * @param PSX\Data\RecordInterface $record
	 * @param PSX\Api\Version $version
	 * @return array|PSX\Data\RecordInterface
	 */
	protected function doCreate(RecordInterface $record, Version $version)
	{
		$this->getValidator()->validate($record);

		$password = sha1(mcrypt_create_iv(40));

		$this->tableManager->getTable('Fusio\Backend\Table\User')->create(array(
			'status'   => $record->getStatus(),
			'name'     => $record->getName(),
			'password' => \password_hash($password, PASSWORD_DEFAULT),
			'date'     => new DateTime(),
		));

		return array(
			'success' => true,
			'message' => 'User successful created. The following password was assigned to the account: ' . $password,
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

		$this->tableManager->getTable('Fusio\Backend\Table\User')->update(array(
			'id'     => $record->getId(),
			'status' => $record->getStatus(),
			'name'   => $record->getName(),
		));

		return array(
			'success' => true,
			'message' => 'User successful updated',
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

		$this->tableManager->getTable('Fusio\Backend\Table\User')->delete(array(
			'id' => $record->getId(),
		));

		return array(
			'success' => true,
			'message' => 'User successful deleted',
		);
	}
}
