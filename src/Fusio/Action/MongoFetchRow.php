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
 * MongoFetchRow
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class MongoFetchRow implements ActionInterface
{
	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 * @var Fusio\ConnectionFactory
	 */
	protected $connectionFactory;

	public function getName()
	{
		return 'Mongo-Fetch-Row';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$connection = $this->connectionFactory->getConnection($configuration->get('connection'));

		if($connection instanceof MongoDB)
		{
			$collection = $configuration->get('collection');
			$collection = $connection->$collection;

			if($collection instanceof MongoCollection)
			{
				$query  = $configuration->get('criteria');
				$query  = !empty($query) ? Json::decode($query) : array();

				$fields = $configuration->get('projection');
				$fields = !empty($fields) ? Json::decode($fields) : array();

				return $collection->findOne($query, $fields);
			}
			else
			{
				throw new ConfigurationException('Invalid collection');
			}
		}
		else
		{
			throw new ConfigurationException('Given connection must be an MongoDB connection');
		}
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Connection('connection', 'Connection', $this->connection));
		$form->add(new Element\Input('collection', 'Collection'));
		$form->add(new Element\TextArea('criteria', 'Criteria', 'json'));
		$form->add(new Element\TextArea('projection', 'Projection', 'json'));

		return $form;
	}
}
