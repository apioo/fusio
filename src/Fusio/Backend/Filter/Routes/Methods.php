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
				throw new StatusCode\BadRequestException('Methods must contain only GET, POST, PUT or DELETE');
			}

			return implode('|', $methods);
		}
		else
		{
			throw new StatusCode\BadRequestException('Methods must not be empty');
		}
	}

	public function getErrorMessage()
	{
		return '%s must contain only alphabetic (A-Z) or numeric (0-9) signs';
	}
}
