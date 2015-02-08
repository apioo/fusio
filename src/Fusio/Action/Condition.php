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
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Condition implements ActionInterface
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
		return 'Condition';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$condition = $configuration->get('condition');
		$language  = new ExpressionLanguage();

		if(empty($condition) || $language->evaluate($condition, $data->toArray()))
		{
			return $this->actionExecutor->execute($configuration->get('true'), $parameters, $data);
		}
		else
		{
			return $this->actionExecutor->execute($configuration->get('false'), $parameters, $data);
		}
	}

	public function getForm()
	{
		$trueElement  = new Element\Select('true', 'True');
		$falseElement = new Element\Select('false', 'False');
		$result       = $this->connection->fetchAll('SELECT id, name FROM fusio_action ORDER BY name ASC');

		foreach($result as $row)
		{
			$trueElement->add($row['id'], $row['name']);
			$falseElement->add($row['id'], $row['name']);
		}

		$form = new Form\Container();
		$form->add(new Element\Input('condition', 'Condition'));
		$form->add($trueElement);
		$form->add($falseElement);

		return $form;
	}
}
