<?php

namespace Fusio\Dependency;

use Fusio\ActionExecutor;
use Fusio\ActionFactory;
use Fusio\ActionParser;
use Fusio\ConnectionFactory;
use Fusio\Loader\RoutingParser;
use Fusio\Data\SchemaManager;
use PSX\Dependency\DefaultContainer;

class Container extends DefaultContainer
{
	/**
	 * @return PSX\Loader\LocationFinderInterface
	 */
	public function getLoaderLocationFinder()
	{
		return new RoutingParser($this->get('connection'));
	}

	/**
	 * @return Fusio\ActionParser
	 */
	public function getActionParser()
	{
		return new ActionParser($this->get('action_factory'), $this->get('config')->get('fusio_action_paths'));
	}

	/**
	 * @return Fusio\ActionFactory
	 */
	public function getActionFactory()
	{
		return new ActionFactory($this->get('object_builder'));
	}

	/**
	 * @return Fusio\ActionExecutor
	 */
	public function getActionExecutor()
	{
		return new ActionExecutor($this->get('connection'), $this->get('action_factory'));
	}

	/**
	 * @return Fusio\ConnectionFactory
	 */
	public function getConnectionFactory()
	{
		return new ConnectionFactory($this->get('connection'));
	}

	/**
	 * @return PSX\Data\Schema\SchemaManagerInterface
	 */
	public function getApiSchemaManager()
	{
		return new SchemaManager($this->get('connection'));
	}
}
