<?php

namespace Fusio\Loader;

use PSX\Loader\RoutingCollection as PSXRoutingCollection;

/**
 * RoutingCollection
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class RoutingCollection extends PSXRoutingCollection
{
	public function add(array $methods, $path, $source, $config = null)
	{
		$this->routings[] = array($methods, $path, $source, $config);
	}
}
