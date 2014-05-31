<?php

namespace Fusio;

use Fusio\Entity\Api;
use Fusio\Entity\App;

class Context
{
	protected $api;
	protected $app;

	public function __construct(Api $api, App $app)
	{
		$this->api = $api;
		$this->app = $app;
	}

	public function getApi()
	{
		return $this->api;
	}

	public function getApp()
	{
		return $this->app;
	}
}
