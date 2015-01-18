<?php

namespace Fusio\Dependency;

use PSX\Dependency\DefaultContainer;
use Fusio\Loader\RoutingParser;

class Container extends DefaultContainer
{
	/**
	 * @return PSX\Loader\RoutingParserInterface
	 */
	public function getRoutingParser()
	{
		return new RoutingParser\Table($this->get('connection'));
	}
}
