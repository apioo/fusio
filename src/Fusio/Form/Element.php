<?php

namespace Fusio\Form;

use PSX\Data\RecordAbstract;

class Element extends RecordAbstract
{
	protected $name;
	protected $title;

	public function __construct($name, $title)
	{
		$this->name  = $name;
		$this->title = $title;
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
}
