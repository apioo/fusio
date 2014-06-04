<?php

namespace Fusio\View;

use Fusio\Context;
use Fusio\Entity\View;
use Fusio\Entity\Model;
use Fusio\ViewAbstract;
use PSX\Data\RecordInterface;
use PSX\Http\Request;
use PSX\Http\Response;

class SqlQuery extends ViewAbstract
{
	public function getName()
	{
		return View::TYPE_SQL_QUERY;
	}

	public function generate(Request $request, Response $response, array $parameters, Model $model, Context $context)
	{
	}
}
