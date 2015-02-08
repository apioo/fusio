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

class CacheResponse implements ActionInterface
{
	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 * @var PSX\Cache
	 */
	protected $cache;

	/**
	 * @Inject
	 * @var Fusio\ActionExecutor
	 */
	protected $actionExecutor;

	public function getName()
	{
		return 'Cache-Response';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$key  = md5('action_' . $configuration->get('action'));
		$item = $this->cache->getItem($key);

		if(!$item->isHit())
		{
			$response = $this->actionExecutor->execute($configuration->get('action'), $parameters, $data);;

			$item->set($response, $configuration->get('expire'));

			$this->cache->save($item);
		}
		else
		{
			$response = $item->get();
		}

		return $response;
	}

	public function getForm()
	{
		$actionElement = new Element\Select('action', 'Action');
		$result        = $this->connection->fetchAll('SELECT id, name FROM fusio_action ORDER BY name ASC');

		foreach($result as $row)
		{
			$actionElement->add($row['id'], $row['name']);
		}

		$form = new Form\Container();
		$form->add($actionElement);
		$form->add(new Element\Input('expire', 'Expire'));

		return $form;
	}
}
