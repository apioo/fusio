<?php

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
				$context->set('fusio.routeId', $row['id']);

				return $request;
			}
		}

		return null;
	}
}
