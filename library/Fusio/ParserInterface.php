<?php

namespace Fusio;

use Fusio\Context;
use Fusio\Entity\Model;
use PSX\Http\Request;
use PSX\Http\Response;

interface ParserInterface
{
	/**
	 * Returns the name of the parser
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Parses the request body and returns an RecordInterface
	 *
	 * @param PSX\Http\Request $request
	 * @param array $parameters
	 * @param Fusio\Entity\Model $model
	 * @param Fusio\Context $context
	 * @return PSX\Data\RecordInterface
	 */
	public function transform(Request $request, array $parameters, Model $model, Context $context);

	/**
	 * Returns an array containing the fields which are needed by the view
	 *
	 * @return array
	 */
	public function getParameters();
}
