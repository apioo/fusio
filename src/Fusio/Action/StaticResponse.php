<?php

namespace Fusio\Action;

use Doctrine\DBAL\Connection;
use Fusio\ActionInterface;
use Fusio\ConfigurationException;
use Fusio\Parameters;
use Fusio\Body;
use Fusio\Form;
use Fusio\Form\Element;

class StaticResponse implements ActionInterface
{
	public function getName()
	{
		return 'Static-Response';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$response = $configuration->get('response');

		if(!empty($response))
		{
			return Json::decode($response);
		}
		else
		{
			throw new ConfigurationException('No response defined');
		}
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\TextArea('response', 'Response'));

		return $form;
	}
}
