<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Loader;

use Doctrine\DBAL\Connection;
use PSX\Http\RequestInterface;
use PSX\Loader\Context;
use PSX\Loader\LocationFinderInterface;
use PSX\Loader\PathMatcher;
use PSX\Loader\RoutingCollection;
use PSX\Loader\RoutingParserInterface;

/**
 * RoutingParser
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class RoutingParser implements LocationFinderInterface
{
	protected $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	public function resolve(RequestInterface $request, Context $context)
	{
		$method      = $request->getMethod();
		$pathMatcher = new PathMatcher($request->getUri()->getPath());
		$result      = $this->connection->fetchAll('SELECT id, methods, path, controller, config FROM fusio_routes');

		foreach($result as $row)
		{
			$parameters = array();

			if(in_array($method, explode('|', $row['methods'])) && 
				$pathMatcher->match($row['path'], $parameters))
			{
				$config = $row['config'];
				$config = !empty($config) ? unserialize($config) : null;

				$context->set(Context::KEY_FRAGMENT, $parameters);
				$context->set(Context::KEY_SOURCE, $row['controller']);
				$context->set('fusio.config', $config);

				return $request;
			}
		}

		return null;
	}
}
