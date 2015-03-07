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
		$message = $this->schemaManager->getSchema('Fusio\Backend\Schema\User\Message');
		$builder = new View\Builder();
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\User\Collection'));
		$builder->setPost($this->schemaManager->getSchema('Fusio\Backend\Schema\User\Create'), $message);

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

		$table = $this->tableManager->getTable('Fusio\Backend\Table\User');
		$table->create(array(
			'status'   => $record->getStatus(),
			'name'     => $record->getName(),
			'password' => \password_hash($password, PASSWORD_DEFAULT),
			'date'     => new DateTime(),
		));

		$this->insertScopes($table->getLastInsertId(), $record->getScopes());

		return array(
			'success'  => true,
			'message'  => 'User successful created',
			'password' => $password,
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

	protected function insertScopes($userId, $scopes)
	{
		if(!empty($scopes) && is_array($scopes))
		{
			$scopeTable = $this->tableManager->getTable('Fusio\Backend\Table\User\Scope');
			$scopes     = $this->tableManager
				->getTable('Fusio\Backend\Table\Scope')
				->getByNames($scopes);

			foreach($scopes as $scope)
			{
				$scopeTable->create(array(
					'userId'  => $userId,
					'scopeId' => $scope['id'],
				));
			}
		}
	}
}
