<?php

namespace Fusio\Action;

use Doctrine\DBAL\Connection;
use Fusio\ActionInterface;
use Fusio\ConfigurationException;
use Fusio\Parameters;
use Fusio\Response;
use Fusio\Body;
use Fusio\Form;
use Fusio\Form\Element;
use PSX\Util\CurveArray;

class SqlQuerySelect implements ActionInterface
{
	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 * @var Fusio\Connector
	 */
	protected $connector;

	public function getName()
	{
		return 'SQL-Query-Select';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$connection = $this->connector->getConnection($configuration->get('connection'));

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

			$result = $connection->fetchAll($sql, $params);
			$key    = $configuration->get('propertyName') ?: 'entry';

			return new Response(200, [], CurveArray::nest(array(
				$key => $result,
			)));
		}
		else
		{
			throw new ConfigurationException('Given connection must be an DBAL connection');
		}
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Connection('connection', 'Connection', $this->connection));
		$form->add(new Element\Input('propertyName', 'Property name'));
		$form->add(new Element\TextArea('sql', 'SQL', 'sql'));

		return $form;
	}
}
