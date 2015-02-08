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
	 * @var Fusio\ActionExecutor
	 */
	protected $actionExecutor;

	public function getName()
	{
		return 'Composite';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$this->actionExecutor->execute($configuration->get('in'), $parameters, $data);

		return $this->actionExecutor->execute($configuration->get('out'), $parameters, $data);
	}

	public function getForm()
	{
		$inElement  = new Element\Select('in', 'In');
		$outElement = new Element\Select('out', 'Out');
		$result     = $this->connection->fetchAll('SELECT id, name FROM fusio_action ORDER BY name ASC');

		foreach($result as $row)
		{
			$inElement->add($row['id'], $row['name']);
			$outElement->add($row['id'], $row['name']);
		}

		$form = new Form\Container();
		$form->add($inElement);
		$form->add($outElement);

		return $form;
	}
}
