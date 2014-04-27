<?php

namespace Fusio\Action;

use Fusio\Parameter;
use PSX\Http\Request;

class CustomResponse extends ActionAbstract
{
	public function execute(Request $request, array $parameters)
	{
	}

	public function getParameters()
	{
		return array(
			new Parameter\Text('template_id', 'Template'),
		);
	}
}
