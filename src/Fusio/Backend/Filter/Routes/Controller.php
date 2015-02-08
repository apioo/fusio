<?php

namespace Fusio\Backend\Filter\Routes;

use PSX\FilterAbstract;

/**
 * Controller
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Controller extends FilterAbstract
{
	public function apply($value)
	{
		$value = str_replace('-', '\\', $value);

		if(class_exists($value))
		{
			return $value;
		}

		return false;
	}

	public function getErrorMessage()
	{
		return '%s must be an valid controller';
	}
}
