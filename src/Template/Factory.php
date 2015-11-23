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

namespace Fusio\Impl\Template;

use Fusio\Engine\Template\FactoryInterface;

/**
 * Factory
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Factory implements FactoryInterface
{
    protected $debug;
    protected $cache;

    public function __construct($debug, $cache = true)
    {
        $this->debug = $debug;
        $this->cache = $cache;
    }

    public function newSqlParser($cacheKey = null)
    {
        return new Parser\Sql($this->debug, $this->cache, $cacheKey);
    }

    public function newTextParser($cacheKey = null)
    {
        return new Parser\Text($this->debug, $this->cache, $cacheKey);
    }
}
