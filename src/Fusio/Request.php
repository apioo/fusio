<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio;

use PSX\Data\RecordInterface;

/**
 * Request
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Request
{
	protected $request;
	protected $uriFragments;
	protected $parameters;
	protected $body;

	public function __construct(RequestInterface $request, array $uriFragments, array $parameters, RecordInterface $body)
	{
		$this->request      = $request;
		$this->uriFragments = $uriFragments;
		$this->parameters   = $parameters;
		$this->body         = $body;
	}

	public function getHeader($name)
	{
		return $this->request->getHeader($name);
	}

	public function getUriFragment($name)
	{
		return isset($this->uriFragments[$name]) ? $this->uriFragments[$name] : null;
	}

	public function getParameter($name)
	{
		return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function withBody(RecordInterface $body)
	{
		$self = clone $this;
		$self->body = $body;

		return $self;
	}
}
