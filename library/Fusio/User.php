<?php

namespace Fusio;

class User
{
	protected $id;
	protected $name;
	protected $authenticated;

	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setAuthenticated($authenticated)
	{
		$this->authenticated = $authenticated;
	}

	public function isAuthenticated()
	{
		return $this->authenticated;
	}
}
