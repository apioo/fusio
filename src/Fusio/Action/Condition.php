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
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Condition
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Condition implements ActionInterface
{
	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 * @var Fusio\Processor
	 */
	protected $processor;

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
			return $this->processor->execute($configuration->get('true'), $parameters, $data);
		}
		else
		{
			return $this->processor->execute($configuration->get('false'), $parameters, $data);
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
