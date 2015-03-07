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
use PSX\Util\CurveArray;

/**
 * CacheResponse
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
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
