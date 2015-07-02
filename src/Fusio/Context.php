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

/**
 * Context
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Context
{
    protected $routeId;
    protected $app;

    public function __construct($routeId, App $app)
    {
        $this->routeId = $routeId;
        $this->app     = $app;
    }

    /**
     * Returns the id of the route
     *
     * @return integer
     */
    public function getRouteId()
    {
        return $this->routeId;
    }

    /**
     * Returns the app which was used for this request. Can also be an anonymous
     * app if authorization is not required for the endpoint
     *
     * @return \Fusio\App
     */
    public function getApp()
    {
        return $this->app;
    }
}
