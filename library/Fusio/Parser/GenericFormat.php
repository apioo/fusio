<?php

namespace Fusio\Parser;

use Fusio\Context;
use Fusio\Entity\Parser;
use Fusio\Entity\Model;
use Fusio\ParserAbstract;
use Fusio\ViewAbstract;
use PSX\Data\RecordInterface;
use PSX\Http\Request;
use PSX\Http\Response;

class GenericFormat extends ParserAbstract
{
	public function getName()
	{
		return Parser::TYPE_GENERIC_FORMAT;
	}

	public function transform(Request $request, array $parameters, Model $model, Context $context)
	{
	}
}
