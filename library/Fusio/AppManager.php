<?php

namespace Fusio;

class AppManager
{
	protected $appRepository;

	public function __construct(AppRepository $appRepository)
	{
		$this->appRepository = $appRepository;
	}

	public function hasPermission(App $app, Api $api, $method)
	{
		
	}
}
