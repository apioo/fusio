<?php

namespace Fusio\Parameter;

use PSX\Data\RecordAbstract;

class Text extends RecordAbstract
{
	protected $type;
	protected $name;
	protected $title;
	protected $value;

	public function __construct($name, $title, $value = null)
	{
		$this->type  = 'text';
		$this->name  = $name;
		$this->title = $title;
		$this->value = $value;
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

	public function setValue($value)
	{
		$this->value = $value;
	}
	
	public function getValue()
	{
		return $this->value;
	}
}
