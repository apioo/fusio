<?php

namespace Fusio;

use PSX\Data\RecordInterface;

interface ActionInterface
{
	/**
	 * An action executes a specific action and returns an array as result. This
	 * could be i.e. an select on an table which returns the result as array or
	 * an push of the incomming message to an mq. On an GET request the $data
	 * is always an empty record. All actions must be placed in the Action 
	 * folder
	 *
	 * @param Fusio\Parameters $parameters
	 * @param Fusio\Body $data
	 * @param Fusio\Parameters $configuration
	 * @return array
	 */
	public function handle(Parameters $parameters, Body $data, Parameters $configuration);

	/**
	 * Returns an form which the user needs to configure before the action can
	 * be used. The entered values get then passed as Configuration to the 
	 * handle method
	 *
	 * @return Fusio\Form\Container
	 */
	public function getForm();
}
