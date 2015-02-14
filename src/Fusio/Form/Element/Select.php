<?php

namespace Fusio\Form\Element;

use Fusio\Form\Element;

class Select extends Element
{
	protected $element = 'http://fusio-project.org/ns/2015/form/select';

	protected $options;

	public function __construct($name, $title, array $options = array())
	{
		parent::__construct($name, $title);

		foreach($options as $key => $value)
		{
			$this->addOption($key, $value);
		}
	}

	public function setOptions(array $options)
	{
		$this->options = $options;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function addOption($key, $value)
	{
		$this->options[] = array(
			'key'   => $key,
			'value' => $value,
		);
	}
}
