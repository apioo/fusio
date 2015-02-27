<?php

namespace Fusio\Dependency;

use Fusio\Backend\Api\Authorization\ClientCredentials;
use Fusio\Executor;
use Fusio\Factory;
use Fusio\Parser;
use Fusio\Logger;
use Fusio\Connector;
use Fusio\Loader\RoutingParser;
use Fusio\Loader\DatabaseRoutes;
use Fusio\Loader\ResourceListing;
use Fusio\Data\SchemaManager;
use PSX\Dependency\DefaultContainer;
use PSX\Oauth2\Provider\GrantTypeFactory;

class Container extends DefaultContainer
{
	public function getOauth2GrantTypeFactory()
	{
		$factory = new GrantTypeFactory();
		$factory->add(new ClientCredentials($this->get('connection')));

		return $factory;
	}

	/**
	 * @return PSX\Loader\LocationFinderInterface
	 */
	public function getRoutingParser()
	{
		return new DatabaseRoutes($this->get('connection'));
	}

	/**
	 * @return PSX\Loader\LocationFinderInterface
	 */
	public function getLoaderLocationFinder()
	{
		return new RoutingParser($this->get('connection'));
	}

	/**
	 * @return PSX\Data\Schema\SchemaManagerInterface
	 */
	public function getApiSchemaManager()
	{
		return new SchemaManager($this->get('connection'));
	}

	/**
	 * @return PSX\Api\ResourceListing
	 */
	public function getResourceListing()
	{
		return new ResourceListing($this->get('routing_parser'), $this->get('controller_factory'));
	}

	/**
	 * @return Fusio\Logger
	 */
	public function getApiLogger()
	{
		return new Logger($this->get('connection'));
	}

	/**
	 * @return Fusio\Parser\Action
	 */
	public function getActionParser()
	{
		return new Parser\Action(
			$this->get('action_factory'), 
			$this->get('config')->get('fusio_action_paths'), 
			'Fusio\ActionInterface'
		);
	}

	/**
	 * @return Fusio\Factory\Action
	 */
	public function getActionFactory()
	{
		return new Factory\Action($this->get('object_builder'));
	}

	/**
	 * @return Fusio\Executor
	 */
	public function getExecutor()
	{
		return new Executor($this->get('connection'), $this->get('action_factory'));
	}

	/**
	 * @return Fusio\Parser\Connection
	 */
	public function getConnectionParser()
	{
		return new Parser\Connection(
			$this->get('connection_factory'), 
			$this->get('config')->get('fusio_connection_paths'), 
			'Fusio\ConnectionInterface'
		);
	}

	/**
	 * @return Fusio\Factory\Connection
	 */
	public function getConnectionFactory()
	{
		return new Factory\Connection($this->get('object_builder'));
	}

	/**
	 * @return Fusio\Connector
	 */
	public function getConnector()
	{
		return new Connector($this->get('connection'), $this->get('connection_factory'));
	}
}
