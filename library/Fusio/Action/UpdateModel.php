<?php

namespace Fusio\Action;

use PSX\Http\Request;

class UpdateModel extends ActionAbstract
{
	public function execute(Request $request, $parameters)
	{
	}

	public function getParameters()
	{
		return array('model_id', 'mapping');
	}
}
