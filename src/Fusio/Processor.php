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

namespace Fusio;

use Doctrine\DBAL\Connection;

/**
 * Processor
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Processor
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
     * @param \Fusio\Request $request
     * @param \Fusio\Context $context
     * @return \Fusio\Response
     */
    public function execute($actionId, Request $request, Context $context)
    {
        $action = $this->connection->fetchAssoc('SELECT id, name, class, config, date FROM fusio_action WHERE id = :id', array('id' => $actionId));

        if (empty($action)) {
            throw new ConfigurationException('Invalid action');
        }

        $config = !empty($action['config']) ? unserialize($action['config']) : array();

        $parameters = new Parameters($config);
        $parameters->set(Parameters::ACTION_ID, $action['id']);
        $parameters->set(Parameters::ACTION_NAME, $action['name']);
        $parameters->set(Parameters::ACTION_LAST_MODIFIED, $action['date']);

        return $this->factory->factory($action['class'])->handle($request, $parameters, $context);
    }
}
