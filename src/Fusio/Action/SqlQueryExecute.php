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

class SqlQueryExecute implements ActionInterface
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
		return 'SQL-Query-Execute';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$connection = $this->connectionFactory->getById($configuration->get('connection'));

		if($connection instanceof Connection)
		{
			$sql    = $configuration->get('sql');
			$params = array();

			foreach($parameters as $key => $value)
			{
				if(strpos($sql, ':' . $key) !== false)
				{
					$params[$key] = $value;
				}
			}

			$connection->execute($sql, $params);

			return array(
				'success' => true,
				'message' => 'Execution was successful'
			);
		}
		else
		{
			throw new ConfigurationException('Given connection must be an DBAL connection');
		}
	}

	public function getForm()
	{
		$sqlElement = new Element\TextArea('sql', 'SQL');
		$sqlElement->setMode('sql');

		$connectionElement = new Element\Select('connection', 'Connection');
		$result = $this->connection->fetchAll('SELECT id, name FROM fusio_connection ORDER BY name ASC');

		foreach($result as $row)
		{
			$connectionElement->add($row['id'], $row['name']);
		}

		$form = new Form\Container();
		$form->add($connectionElement);
		$form->add($sqlElement);

		return $form;
	}
}
