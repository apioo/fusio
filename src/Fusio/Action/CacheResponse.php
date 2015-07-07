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
use Fusio\Context;
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use Fusio\Processor;
use Fusio\Request;
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
        return 'Cache-Response';
    }

    public function handle(Request $request, Parameters $configuration, Context $context)
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

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Action('action', 'Action', $this->connection, 'The response of this action gets cached'));
        $form->add(new Element\Input('expire', 'Expire', 'text', 'Number of seconds when the cache expires'));

        return $form;
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
