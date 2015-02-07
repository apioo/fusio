<?php

namespace Fusio\Form\Element;

use Fusio\Form\Element;

class Input extends Element
{
	protected $element = 'http://fusio-project.org/ns/2015/form/input';
	protected $type;

	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getType()
	{
		return $this->type;
	}
}
