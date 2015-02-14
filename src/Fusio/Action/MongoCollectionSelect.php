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

class MongoCollectionSelect implements ActionInterface
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
		return 'Mongo-Collection-Select';
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

				return $collection->find($query, $fields);
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
		$form->add(new Element\TextArea('criteria', 'Criteria'));
		$form->add(new Element\TextArea('projection', 'Projection'));

		return $form;
	}
}
