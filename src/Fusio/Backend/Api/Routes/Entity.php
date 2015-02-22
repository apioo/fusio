<?php

namespace Fusio\Backend\Api\Routes;

use PSX\Api\Documentation;
use PSX\Api\Version;
use PSX\Api\View;
use PSX\Controller\SchemaApiAbstract;
use PSX\Data\RecordInterface;
use PSX\Http\Exception as StatusCode;
use PSX\Sql\Condition;

/**
 * Routes
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
		$builder = new View\Builder();
		$builder->setGet($this->schemaManager->getSchema('Fusio\Backend\Schema\Routes'));
		$builder->setPut($this->schemaManager->getSchema('Fusio\Backend\Schema\Routes\Update'), $message);
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
		$routeId = (int) $this->getUriFragment('route_id');
		$route   = $this->tableManager->getTable('Fusio\Backend\Table\Routes')->get($routeId);

		if(!empty($route))
		{
			return $route;
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find route');
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
		$routeId = (int) $this->getUriFragment('route_id');
		$route   = $this->tableManager->getTable('Fusio\Backend\Table\Routes')->get($routeId);

		if(!empty($route))
		{
			$this->getValidator()->validate($record);

			// replace dash with backslash
			$controller = str_replace('-', '\\', $record->getController());

			$this->tableManager->getTable('Fusio\Backend\Table\Routes')->update(array(
				'id'         => $route['id'],
				'methods'    => $record->getMethods(),
				'path'       => $record->getPath(),
				'controller' => $controller,
				'config'     => $record->getConfig(),
			));

			return array(
				'success' => true,
				'message' => 'Routes successful updated',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find route');
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
		$routeId = (int) $this->getUriFragment('route_id');
		$route   = $this->tableManager->getTable('Fusio\Backend\Table\Routes')->get($routeId);

		if(!empty($route))
		{
			$this->tableManager->getTable('Fusio\Backend\Table\Routes')->delete(array(
				'id' => $route['id']
			));

			return array(
				'success' => true,
				'message' => 'Routes successful deleted',
			);
		}
		else
		{
			throw new StatusCode\NotFoundException('Could not find route');
		}
	}
}
