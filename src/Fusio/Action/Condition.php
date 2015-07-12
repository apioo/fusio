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

namespace Fusio\Action;

use Doctrine\DBAL\Connection;
use Fusio\ActionInterface;
use Fusio\App\RateLimit;
use Fusio\Context;
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use Fusio\Processor;
use Fusio\Request;
use PSX\Cache;
use PSX\Data\Accessor;
use PSX\Validate;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\ParsedExpression;
use Symfony\Component\ExpressionLanguage\ParserCache\ParserCacheInterface;

/**
 * Condition
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Condition implements ActionInterface, ParserCacheInterface
{
    /**
     * @Inject
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @Inject
     * @var \Fusio\Processor
     */
    protected $processor;

    /**
     * @Inject
     * @var \PSX\Cache
     */
    protected $cache;

    public function getName()
    {
        return 'Condition';
    }

    public function handle(Request $request, Parameters $configuration, Context $context)
    {
        $condition = $configuration->get('condition');
        $language  = new ExpressionLanguage($this);
        $values    = array(
            'rateLimit'    => new RateLimit($this->connection, $context),
            'app'          => $context->getApp(),
            'routeId'      => $context->getRouteId(),
            'uriFragments' => $request->getUriFragments(),
            'parameters'   => $request->getParameters(),
            'body'         => new Accessor(new Validate(), $request->getBody()),
        );

        if (!empty($condition) && $language->evaluate($condition, $values)) {
            return $this->processor->execute($configuration->get('true'), $request, $context);
        } else {
            return $this->processor->execute($configuration->get('false'), $request, $context);
        }
    }

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Input('condition', 'Condition'));
        $form->add(new Element\Action('true', 'True', $this->connection));
        $form->add(new Element\Action('false', 'False', $this->connection));

        return $form;
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

    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function setProcessor(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }
}
