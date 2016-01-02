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

namespace Fusio\Impl\Form;

use Doctrine\DBAL\Connection;
use Fusio\Engine\Form\ElementFactoryInterface;

/**
 * ElementFactory
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ElementFactory implements ElementFactoryInterface
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function newAction($name, $title, $help = null)
    {
        return new Element\Action($name, $title, $this->connection, $help);
    }

    public function newConnection($name, $title, $help = null)
    {
        return new Element\Connection($name, $title, $this->connection, $help);
    }

    public function newInput($name, $title, $type = 'text', $help = null)
    {
        return new Element\Input($name, $title, $type, $help);
    }

    public function newSelect($name, $title, array $options = array(), $help = null)
    {
        return new Element\Select($name, $title, $options, $help);
    }

    public function newTextArea($name, $title, $mode, $help = null)
    {
        return new Element\TextArea($name, $title, $mode, $help);
    }
}
