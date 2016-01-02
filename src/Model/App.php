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

namespace Fusio\Impl\Model;

use Fusio\Engine\Model\AppInterface;

/**
 * App
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class App implements AppInterface
{
    protected $anonymous;
    protected $id;
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

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
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
