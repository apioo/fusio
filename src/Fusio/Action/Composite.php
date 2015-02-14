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

class Composite implements ActionInterface
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
		return 'Composite';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$this->executor->execute($configuration->get('in'), $parameters, $data);

		return $this->executor->execute($configuration->get('out'), $parameters, $data);
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Action('in', 'In', $this->connection));
		$form->add(new Element\Action('out', 'Out', $this->connection));

		return $form;
	}
}
