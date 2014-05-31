<?php

namespace Fusio\Api;

use Exception;

class InvalidRequestMethodException extends Exception
{
	protected $actualMethod;
	protected $allowedMethods;

	public function __construct($message, $code, $actualMethod, $allowedMethods)
	{
		parent::__construct($message, $code);

		$this->actualMethod   = $actualMethod;
		$this->allowedMethods = $allowedMethods;
	}
}
