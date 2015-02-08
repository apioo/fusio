<?php

namespace Fusio;

class Body
{
	public function toArray()
	{
		return array();
	}

	public static function fromArray(array $data)
	{
		return new self();
	}
}
