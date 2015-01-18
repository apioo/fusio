<?php

namespace Fusio\Backend\Filter\Routes;

use PSX\FilterAbstract;
use PSX\Http\Exception as StatusCode;

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
		return true;
	}

	public function getErrorMessage()
	{
		return '%s must contain only alphabetic (A-Z) or numeric (0-9) signs';
	}
}
