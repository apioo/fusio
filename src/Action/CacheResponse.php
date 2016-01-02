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

namespace Fusio\Impl\Action;

use Doctrine\DBAL\Connection;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\ProcessorInterface;
use Fusio\Engine\RequestInterface;
use PSX\Cache;

/**
 * CacheResponse
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class CacheResponse implements ActionInterface
{
    /**
     * @Inject
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @Inject
     * @var \Fusio\Engine\ProcessorInterface
     */
    protected $processor;

    /**
     * @Inject
     * @var \PSX\Cache
     */
    protected $cache;

    public function getName()
    {
        return 'Cache-Response';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $key  = md5($configuration->get('action'));
        $item = $this->cache->getItem($key);

        if (!$item->isHit()) {
            $response = $this->processor->execute($configuration->get('action'), $request, $context);

            $item->set($response, $configuration->get('expire'));

            $this->cache->save($item);
        } else {
            $response = $item->get();
        }

        return $response;
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newAction('action', 'Action', 'The response of this action gets cached'));
        $builder->add($elementFactory->newInput('expire', 'Expire', 'text', 'Number of seconds when the cache expires'));
    }

    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function setProcessor(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }
}
