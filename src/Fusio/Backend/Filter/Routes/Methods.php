<?php

namespace Fusio\Backend\Filter\Routes;

use PSX\FilterAbstract;

/**
 * Methods
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Methods extends FilterAbstract
{
	public function apply($value)
	{
		$methods = explode('|', strtoupper($value));
		if(!empty($methods))
		{
			$methods    = array_unique($methods);
			$methodDiff = array_diff($methods, array('GET', 'POST', 'PUT', 'DELETE'));
			if(count($methodDiff) > 0)
			{
				return false;
			}

			return implode('|', $methods);
		}

		return false;
	}

	public function getErrorMessage()
	{
		return '%s must contain only GET, POST, PUT or DELETE';
	}
}
