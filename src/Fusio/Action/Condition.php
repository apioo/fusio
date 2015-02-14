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
	 * @var Fusio\Executor
	 */
	protected $executor;

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
			return $this->executor->execute($configuration->get('true'), $parameters, $data);
		}
		else
		{
			return $this->executor->execute($configuration->get('false'), $parameters, $data);
		}
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Input('condition', 'Condition'));
		$form->add(new Element\Action('true', 'True', $this->connection));
		$form->add(new Element\Action('false', 'False', $this->connection));

		return $form;
	}
}
