<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

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

/**
 * StaticResponse
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
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
