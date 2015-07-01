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
 * App
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class App
{
    const STATUS_ACTIVE      = 0x1;
    const STATUS_PENDING     = 0x2;
    const STATUS_DEACTIVATED = 0x3;

    protected $anonymous;
    protected $routeId;
    protected $userId;
    protected $status;
    protected $name;
    protected $url;
    protected $appKey;
    protected $scopes;

    public function setAnonymous($anonymous)
    {
        $this->anonymous = $anonymous;
    }

    public function isAnonymous()
    {
        return $this->anonymous;
    }

    public function setRouteId($routeId)
    {
        $this->routeId = $routeId;
    }

    public function getRouteId()
    {
        return $this->routeId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    
    public function getUserId()
    {
        return $this->userId;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    public function getStatus()
    {
        return $this->status;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function getUrl()
    {
        return $this->url;
    }

    public function setAppKey($appKey)
    {
        $this->appKey = $appKey;
    }
    
    public function getAppKey()
    {
        return $this->appKey;
    }

    public function setScopes(array $scopes)
    {
        $this->scopes = $scopes;
    }
    
    public function getScopes()
    {
        return $this->scopes;
    }

    public function hasScope($name)
    {
        return in_array($name, $this->scopes);
    }
}
