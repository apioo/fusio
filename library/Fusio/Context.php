<?php

namespace Fusio;

use Fusio\Entity\Api;
use PSX\Http\Request;
use PSX\Http\Response;

class Context
{
	protected $api;
	protected $app;
	protected $request;
	protected $response;

	public function __construct(Api $api, App $app, Request $request, Response $response)
	{
		$this->api      = $api;
		$this->app      = $app;
		$this->request  = $request;
		$this->response = $response;
	}

	public function getApi()
	{
		return $this->api;
	}

	public function getApp()
	{
		return $this->app;
	}

	public function getRequest()
	{
		return $this->request;
	}

	public function getResponse()
	{
		return $this->response;
	}
}
