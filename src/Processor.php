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
use Fusio\Impl\Model\Action;

/**
 * Processor
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Processor implements ProcessorInterface
{
    protected $connection;
    protected $factory;

    public function __construct(Connection $connection, Factory\Action $factory)
    {
        $this->connection = $connection;
        $this->factory    = $factory;
    }

    /**
     * @param integer $actionId
     * @param \Fusio\Engine\RequestInterface $request
     * @param \Fusio\Engine\ContextInterface $context
     * @return \Fusio\Engine\ResponseInterface
     */
    public function execute($actionId, RequestInterface $request, ContextInterface $context)
    {
        if (is_numeric($actionId)) {
            $column = 'id';
        } else {
            $column = 'name';
        }

        $row = $this->connection->fetchAssoc('SELECT id, name, class, config, date FROM fusio_action WHERE ' . $column . ' = :id', array('id' => $actionId));

        if (empty($row)) {
            throw new ConfigurationException('Invalid action');
        }

        $config = !empty($row['config']) ? unserialize($row['config']) : array();
        $parameters = new Parameters($config);

        $action = new Action();
        $action->setId($row['id']);
        $action->setName($row['name']);
        $action->setDate($row['date']);

        return $this->factory->factory($row['class'])->handle($request, $parameters, $context->withAction($action));
    }
}
