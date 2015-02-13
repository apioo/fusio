<?php

namespace Fusio;

use ArrayIterator;
use IteratorAggregate;

class Response
{
	public function __construct($statusCode, array $headers, $body)
	{
		$this->statusCode = $statusCode;
		$this->headers    = $headers;
		$this->body       = $body;
	}

	public function getStatusCode()
	{
		return $this->statusCode;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function getBody()
	{
		return $this->body;
	}
}
