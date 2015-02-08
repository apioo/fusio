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
	 * @var Fusio\ActionExecutor
	 */
	protected $actionExecutor;

	public function getName()
	{
		return 'Pipe';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$response = $this->actionExecutor->execute($configuration->get('source'), $parameters, $data);
		$data     = Body::fromArray($response);

		return $this->actionExecutor->execute($configuration->get('destination'), $parameters, $data);
	}

	public function getForm()
	{
		$srcElement  = new Element\Select('source', 'Source');
		$destElement = new Element\Select('destination', 'Destination');
		$result      = $this->connection->fetchAll('SELECT id, name FROM fusio_action ORDER BY name ASC');

		foreach($result as $row)
		{
			$srcElement->add($row['id'], $row['name']);
			$destElement->add($row['id'], $row['name']);
		}

		$form = new Form\Container();
		$form->add($srcElement);
		$form->add($destElement);

		return $form;
	}
}
