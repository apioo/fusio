<?php

namespace Fusio;

use Fusio\ConnectionInterface;

interface ConfigurableInterface
{
	/**
	 * Returns an human readable string which represents this resource
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Returns an form which the user needs to configure before the action can
	 * be used. The entered values get then passed as Configuration to the 
	 * handle method
	 *
	 * @return Fusio\Form\Container
	 */
	public function getForm();
}
