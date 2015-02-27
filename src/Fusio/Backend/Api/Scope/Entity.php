<?php

namespace Fusio\Backend\Api\Scope;

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
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Scope'));
		$builder->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\Scope\Update'), $message);
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
		$scopeId = (int) $this->getUriFragment('scope_id');
		$scope   = $this->tableManager->getTable('Fusio\Backend\Table\Scope')->get($scopeId);

		if(!empty($scope))
		{
			return $scope;
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find scope');
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
		$scopeId = (int) $this->getUriFragment('scope_id');
		$scope   = $this->tableManager->getTable('Fusio\Backend\Table\Scope')->get($scopeId);

		if(!empty($scope))
		{
			$this->getValidator()->validate($record);

			$this->tableManager->getTable('Fusio\Backend\Table\Scope')->update(array(
				'id'   => $scope['id'],
				'name' => $record->getName(),
			));

			$this->tableManager->getTable('Fusio\Backend\Table\Scope\Route')->deleteAllFromScope($record->getId());

			$this->insertRoutes($record->getId(), $record->getRoutes());

			return array(
				'success' => true,
				'message' => 'Scope successful updated',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find scope');
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
		$scopeId = (int) $this->getUriFragment('scope_id');
		$scope   = $this->tableManager->getTable('Fusio\Backend\Table\Scope')->get($scopeId);

		if(!empty($scope))
		{
			$this->tableManager->getTable('Fusio\Backend\Table\Scope')->delete(array(
				'id' => $scope['id']
			));

			$this->tableManager->getTable('Fusio\Backend\Table\Scope\Route')->deleteAllFromScope($scope['id']);

			return array(
				'success' => true,
				'message' => 'Scope successful deleted',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find scope');
		}
	}

	protected function insertRoutes($scopeId, $routes)
	{
		if(!empty($routes) && is_array($routes))
		{
			foreach($routes as $route)
			{
				//$this->getFieldValidator()->validate($field);

				if($route->getAllow())
				{
					$this->tableManager->getTable('Fusio\Backend\Table\Scope\Route')->create(array(
						'scopeId' => $scopeId,
						'routeId' => $route->getRouteId(),
						'allow'   => $route->getAllow() ? 1 : 0,
						'methods' => $route->getMethods(),
					));
				}
			}
		}
	}
}
