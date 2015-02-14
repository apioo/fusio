<?php

namespace Fusio\Action;

use Doctrine\DBAL\Connection;
use Fusio\ActionInterface;
use Fusio\ConfigurationException;
use Fusio\Parameters;
use Fusio\Body;
use Fusio\Form;
use Fusio\Form\Element;
use PSX\Util\CurveArray;

class Pipe implements ActionInterface
{
	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 * @var Fusio\Executor
	 */
	protected $executor;

	public function getName()
	{
		return 'Pipe';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$response = $this->executor->execute($configuration->get('source'), $parameters, $data);
		$data     = Body::fromArray($response);

		return $this->executor->execute($configuration->get('destination'), $parameters, $data);
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Action('source', 'Source', $this->connection));
		$form->add(new Element\Action('destination', 'Destination', $this->connection));

		return $form;
	}
}
