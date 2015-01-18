<?php

namespace Fusio\Backend\Filter\Routes;

use PSX\FilterAbstract;

/**
 * Path
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Path extends FilterAbstract
{
	public function apply($value)
	{
		if(!empty($value))
		{
			if(substr($value, 0, 1) != '/')
			{
				throw new StatusCode\BadRequestException('Path must start with an /');
			}
		}
		else
		{
			throw new BadRequestException('Path must not be empty');
		}
	}

	public function getErrorMessage()
	{
		return '%s must contain only alphabetic (A-Z) or numeric (0-9) signs';
	}
}
