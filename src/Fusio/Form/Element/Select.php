<?php

namespace Fusio\Form\Element;

use Fusio\Form\Element;

class Select extends Element
{
	protected $element = 'http://fusio-project.org/ns/2015/form/select';

	public function __construct($name, $title, array $value = array(), $toolTip = null)
	{
		parent::__construct($name, $title, $value, $toolTip);
	}

	public function add($key, $value)
	{
		$this->value[] = array(
			'key'   => $key,
			'value' => $value,
		);
	}
}
