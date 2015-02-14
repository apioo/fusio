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
	 * @var Fusio\Executor
	 */
	protected $executor;

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
			$response = $this->executor->execute($configuration->get('action'), $parameters, $data);;

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
		$form = new Form\Container();
		$form->add(new Element\Action('action', 'Action', $this->connection));
		$form->add(new Element\Input('expire', 'Expire'));

		return $form;
	}
}
