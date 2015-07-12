<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Loader;

use Doctrine\DBAL\Connection;
use PSX\Http\RequestInterface;
use PSX\Loader\Context;
use PSX\Loader\LocationFinderInterface;
use PSX\Loader\PathMatcher;

/**
 * RoutingParser
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
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
        $sql = 'SELECT id,
				       methods,
				       path,
				       controller,
				       config
				  FROM fusio_routes
				 WHERE status = 1
				   AND methods LIKE :method';

        $method      = $request->getMethod();
        $pathMatcher = new PathMatcher($request->getUri()->getPath());
        $result      = $this->connection->fetchAll($sql, array(
            'method' => '%' . $method . '%'
        ));

        foreach ($result as $row) {
            $parameters = array();

            if (in_array($method, explode('|', $row['methods'])) &&
                $pathMatcher->match($row['path'], $parameters)) {
                $config = $row['config'];
                $config = !empty($config) ? unserialize($config) : null;

                $context->set(Context::KEY_FRAGMENT, $parameters);
                $context->set(Context::KEY_PATH, $row['path']);
                $context->set(Context::KEY_SOURCE, $row['controller']);
                $context->set('fusio.config', $config);
                $context->set('fusio.routeId', $row['id']);

                return $request;
            }
        }

        return null;
    }
}
