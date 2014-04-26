<?php

namespace Fusio;

use PSX\Http\Request;

interface ActionInterface
{
	public function execute(Request $request, $parameters);

	public function getParameters();
}
