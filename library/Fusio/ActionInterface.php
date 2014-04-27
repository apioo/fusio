<?php

namespace Fusio;

use PSX\Http\Request;

interface ActionInterface
{
	public function execute(Request $request, array $parameters);

	public function getParameters();
}
