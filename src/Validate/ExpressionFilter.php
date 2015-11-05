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

namespace Fusio\Impl\Validate;

use PSX\Cache;
use PSX\FilterAbstract;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\ParsedExpression;
use Symfony\Component\ExpressionLanguage\ParserCache\ParserCacheInterface;

/**
 * ExpressionFilter
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ExpressionFilter extends FilterAbstract implements ParserCacheInterface
{
    protected $container;
    protected $cache;
    protected $expression;
    protected $message;

    public function __construct(ServiceContainer $container, Cache $cache, $expression, $message)
    {
        $this->container  = $container;
        $this->cache      = $cache;
        $this->expression = $expression;
        $this->message    = $message;
    }

    public function apply($value)
    {
        $language = new ExpressionLanguage($this);
        $values   = array();

        foreach ($this->container as $name => $service) {
            $values[$name] = $service;
        }

        $values['value'] = $value;

        return $language->evaluate($this->expression, $values);
    }

    public function getErrorMessage()
    {
        return $this->message;
    }

    public function save($key, ParsedExpression $expression)
    {
        $item = $this->cache->getItem(md5($key));
        $item->set($expression);

        $this->cache->save($item);
    }

    public function fetch($key)
    {
        $item = $this->cache->getItem(md5($key));

        return $item->isHit() ? $item->get() : null;
    }
}
