<?php

namespace Fusio;

class ParserFactory
{
	protected $parsers = array();

	public function registerParser(ParserInterface $parser)
	{
		$this->parsers[] = $parser;
	}

	public function factory($type)
	{
		foreach($this->parsers as $parser)
		{
			if($parser->getType() == $type)
			{
				return $parser;
			}
		}
	}
}
