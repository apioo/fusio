<?php

namespace Fusio\Action;

use PSX\Http\Request;

class Void extends ActionAbstract
{
	public function execute(Request $request, $parameters)
	{
	}

	public function getParameters()
	{
		return array();
	}
}
