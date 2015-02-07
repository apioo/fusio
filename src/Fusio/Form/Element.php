<?php

namespace Fusio\Form;

use PSX\Data\RecordAbstract;

class Element extends RecordAbstract
{
	protected $name;
	protected $value;
	protected $title;
	protected $toolTip;

	public function __construct($name, $title, $value = null, $toolTip = null)
	{
		$this->name    = $name;
		$this->title   = $title;
		$this->value   = $value;
		$this->toolTip = $toolTip;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}
	
	public function getValue()
	{
		return $this->value;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function getTitle()
	{
		return $this->title;
	}

	public function setToolTip($toolTip)
	{
		$this->toolTip = $toolTip;
	}
	
	public function getToolTip()
	{
		return $this->toolTip;
	}
}
