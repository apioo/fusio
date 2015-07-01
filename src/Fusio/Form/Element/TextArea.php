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

namespace Fusio\Form\Element;

use Fusio\Form\Element;

/**
 * TextArea
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class TextArea extends Element
{
    protected $element = 'http://fusio-project.org/ns/2015/form/textarea';
    protected $mode;

    public function __construct($name, $title, $mode, $help = null)
    {
        parent::__construct($name, $title, $help);

        $this->mode = $mode;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }
    
    public function getMode()
    {
        return $this->mode;
    }
}
