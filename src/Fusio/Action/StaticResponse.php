<?php

namespace Fusio\Action;

use Doctrine\DBAL\Connection;
use Fusio\ActionInterface;
use Fusio\ConfigurationException;
use Fusio\Parameters;
use Fusio\Body;
use Fusio\Response;
use Fusio\Form;
use Fusio\Form\Element;
use PSX\Json;

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
			return new Response(200, [], Json::decode($response));
		}
		else
		{
			throw new ConfigurationException('No response defined');
		}
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\TextArea('response', 'Response', 'json'));

		return $form;
	}
}
