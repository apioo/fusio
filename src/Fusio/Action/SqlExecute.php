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
use Fusio\Response;
use Fusio\Form;
use Fusio\Form\Element;

/**
 * SqlExecute
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class SqlExecute implements ActionInterface
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
		return 'SQL-Execute';
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

			// replace body parameters
			preg_match_all('/{{([A-z0-9\.]+)}}/', $sql, $matches);

			$keys = isset($matches[1]) ? $matches[1] : array();

			foreach($keys as $index => $name)
			{
				$value = $data->get($name) ?: null;
				$key   = 'body_' . $index;

				$params[$key] = $value;

				$sql = str_replace('{{' . $name . '}}', ':' . $key, $sql);
			}

			$connection->execute($sql, $params);

			return new Response(200, [], array(
				'success' => true,
				'message' => 'Execution was successful'
			));
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
		$form->add(new Element\TextArea('sql', 'SQL', 'sql'));

		return $form;
	}
}
