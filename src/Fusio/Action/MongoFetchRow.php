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
use Fusio\Context;
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use Fusio\Request;
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

	public function handle(Request $request, Parameters $configuration, Context $context)
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

				$result = $collection->findOne($query, $fields);

				if(empty($result))
				{
					throw new StatusCode\NotFoundException('Entry not available');
				}

				return new Response(200, [], $result);
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
		$form->add(new Element\Connection('connection', 'Connection', $this->connection, 'The MongoDB connection which should be used'));
		$form->add(new Element\Input('collection', 'Collection', 'text', 'The data gets fetched from this collection'));
		$form->add(new Element\TextArea('criteria', 'Criteria', 'json', 'Specifies selection criteria using <a href="http://docs.mongodb.org/manual/reference/operator/">query operators</a>. To return all documents in a collection, omit this parameter or pass an empty document ({})'));
		$form->add(new Element\TextArea('projection', 'Projection', 'json', 'Specifies the fields to return using <a href="http://docs.mongodb.org/manual/reference/operator/projection/">projection operators</a>. To return all fields in the matching document, omit this parameter.'));

		return $form;
	}
}
