<?php

namespace Fusio\Action;

use PSX\Http\Request;

class SelectFields extends ActionAbstract
{
	public function execute(Request $request, $parameters)
	{
	}

	public function getParameters()
	{
		return array('connection_id', 'table', 'fields');
	}
}
