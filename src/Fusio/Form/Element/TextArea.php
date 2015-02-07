<?php

namespace Fusio\Form\Element;

use Fusio\Form\Element;

class TextArea extends Element
{
	protected $element = 'http://fusio-project.org/ns/2015/form/textarea';
	protected $mode;

	public function setMode($mode)
	{
		$this->mode = $mode;
	}
	
	public function getMode()
	{
		return $this->mode;
	}
}
