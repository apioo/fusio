<?php

namespace Fusio\Dependency;

use Fusio\ActionFactory;
use Fusio\ActionParser;
use Fusio\ConnectionFactory;
use Fusio\Loader\RoutingParser;
use PSX\Dependency\DefaultContainer;

class Container extends DefaultContainer
{
	/**
	 * @return PSX\Loader\RoutingParserInterface
	 */
	public function getRoutingParser()
	{
		return new RoutingParser\Table($this->get('connection'));
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
	 * @return Fusio\ConnectionFactory
	 */
	public function getConnectionFactory()
	{
		return new ConnectionFactory($this->get('connection'));
	}
}
