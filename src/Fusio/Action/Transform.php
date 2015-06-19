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
use Fusio\Form;
use Fusio\Form\Element;
use MongoCollection;
use MongoDB;

/**
 * Transform
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Transform implements ActionInterface
{
	/**
	 * @Inject
	 * @var Fusio\Processor
	 */
	protected $processor;

	public function getName()
	{
		return 'Transform';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$patch = new Patch($configuration->get('patch'));
		$data  = $patch->apply($data->getData());

		return $this->processor->execute($configuration->get('action'), $parameters, new Body($data));
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Action('action', 'Action', $this->connection, 'Executes this action with the transformed result'));
		$form->add(new Element\TextArea('patch', 'Patch', 'json', 'The transformation rules using the <a href="https://tools.ietf.org/html/rfc6902">JSON Patch</a> format'));

		return $form;
	}
}
