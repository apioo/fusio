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

namespace Fusio\Impl;

use Doctrine\DBAL\Connection;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ProcessorInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Model\ActionInterface;
use Fusio\Impl\Model\Action;
use RuntimeException;

/**
 * Processor
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Processor implements ProcessorInterface
{
    protected $stack;
    protected $factory;

    public function __construct(Processor\RepositoryInterface $repository, Factory\Action $factory)
    {
        $this->stack   = [];
        $this->factory = $factory;

        $this->push($repository);
    }

    /**
     * @param integer $actionId
     * @param \Fusio\Engine\RequestInterface $request
     * @param \Fusio\Engine\ContextInterface $context
     * @return \Fusio\Engine\ResponseInterface
     */
    public function execute($actionId, RequestInterface $request, ContextInterface $context)
    {
        $repository = end($this->stack);
        $action     = $repository->getAction($actionId);

        if ($action instanceof ActionInterface) {
            $parameters = new Parameters($action->getConfig());

            return $this->factory->factory($action->getClass())->handle($request, $parameters, $context->withAction($action));
        } else {
            throw new ConfigurationException('Could not found action ' . $actionId);
        }
    }

    /**
     * Pushes another repository to the processor stack. Through this it is
     * possible to provide another action source
     *
     * @param \Fusio\Impl\Processor\RepositoryInterface
     */
    public function push(Processor\RepositoryInterface $repository)
    {
        array_push($this->stack, $repository);
    }

    public function pop()
    {
        if (count($this->stack) === 1) {
            throw new RuntimeException('One repository must be at least available');
        }

        array_pop($this->stack);
    }
}
