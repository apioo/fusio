<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <k42b3.x@gmail.com>
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

use Fusio\Engine\ContextInterface;
use Fusio\Engine\Model\ActionInterface;
use Fusio\Engine\Model\AppInterface;

/**
 * Context
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Context implements ContextInterface
{
    protected $routeId;
    protected $app;
    protected $action;
    protected $connection;

    public function __construct($routeId, AppInterface $app, ActionInterface $action = null)
    {
        $this->routeId = $routeId;
        $this->app     = $app;
        $this->action  = $action;
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
     * @return \Fusio\Engine\Model\AppInterface
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Returns the current action
     *
     * @return \Fusio\Engine\Model\ActionInterface
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Creates a new context containing the given action
     *
     * @param \Fusio\Engine\Model\ActionInterface $action
     * @return \Fusio\Engine\ContextInterface
     */
    public function withAction(ActionInterface $action)
    {
        $me = clone $this;
        $me->action = $action;

        return $me;
    }

    /**
     * Returns the connection which is currently used by the action
     *
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Creates a new context containing the given connection
     *
     * @param mixed $connection
     * @return \Fusio\Engine\ContextInterface
     */
    public function withConnection($connection)
    {
        $me = clone $this;
        $me->connection = $connection;

        return $me;
    }
}
