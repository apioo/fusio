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

use Fusio\Engine\Model\ActionInterface;

/**
 * Action
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Action implements ActionInterface
{
    protected $id;
    protected $name;
    protected $class;
    protected $config;
    protected $date;

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

    public function setClass($class)
    {
        $this->class = $class;
    }
    
    public function getClass()
    {
        return $this->class;
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }
    
    public function getConfig()
    {
        return $this->config;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }
    
    public function getDate()
    {
        return $this->date;
    }
}
