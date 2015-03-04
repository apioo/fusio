<?php

namespace Fusio\Backend\Api\User;

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
 * @see http://phpsx.org/doc/design/controller.html
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
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\User'));
		$builder->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\User\Update'), $message);
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
		$userId = (int) $this->getUriFragment('user_id');
		$user   = $this->tableManager->getTable('Fusio\Backend\Table\User')->get($userId);

		if(!empty($user))
		{
			return $user;
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find user');
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
		$userId = (int) $this->getUriFragment('user_id');
		$user   = $this->tableManager->getTable('Fusio\Backend\Table\User')->get($userId);

		if(!empty($user))
		{
			$this->getValidator()->validate($record);

			$this->tableManager->getTable('Fusio\Backend\Table\User')->update(array(
				'id'     => $user['id'],
				'status' => $record->getStatus(),
				'name'   => $record->getName(),
			));

			return array(
				'success' => true,
				'message' => 'User successful updated',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find user');
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
		$userId = (int) $this->getUriFragment('user_id');
		$user   = $this->tableManager->getTable('Fusio\Backend\Table\User')->get($userId);

		if(!empty($user))
		{
			$this->tableManager->getTable('Fusio\Backend\Table\User')->delete(array(
				'id' => $user['id']
			));

			return array(
				'success' => true,
				'message' => 'User successful deleted',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find user');
		}
	}
}
