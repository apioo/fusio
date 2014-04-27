<?php

namespace Fusio\Parameter;

use PSX\Data\RecordAbstract;

class MultipleChoice extends RecordAbstract
{
	protected $type;
	protected $name;
	protected $title;
	protected $choices;
	protected $value;

	public function __construct($name, $title, array $choices, $value = null)
	{
		$this->type    = 'choice';
		$this->name    = $name;
		$this->choices = $choices;
		$this->value   = $value;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function getTitle()
	{
		return $this->title;
	}

	public function setChoices(array $choices)
	{
		$this->choices = $choices;
	}
	
	public function getChoices()
	{
		return $this->choices;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}
	
	public function getValue()
	{
		return $this->value;
	}
}
