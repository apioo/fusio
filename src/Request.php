<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Impl;

use Fusio\Engine\RequestInterface;
use PSX\Data\RecordInterface;
use PSX\Http\RequestInterface as HttpRequestInterface;

/**
 * Request
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Request implements RequestInterface
{
    protected $request;
    protected $uriFragments;
    protected $parameters;
    protected $body;

    public function __construct(HttpRequestInterface $request, array $uriFragments, array $parameters, RecordInterface $body)
    {
        $this->request      = $request;
        $this->uriFragments = new Parameters($uriFragments);
        $this->parameters   = new Parameters($parameters);
        $this->body         = $body;
    }

    public function getHeader($name)
    {
        return $this->request->getHeader($name);
    }

    public function getUriFragment($name)
    {
        return $this->uriFragments->get($name);
    }

    public function getUriFragments()
    {
        return $this->uriFragments;
    }

    public function getParameter($name)
    {
        return $this->parameters->get($name);
    }

    public function getParameters()
    {
        return $this->parameters;
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
