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
use Fusio\Engine\ConnectorInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Response\FactoryInterface as ResponseFactoryInterface;
use Fusio\Impl\ConfigurationException;
use Pheanstalk\Pheanstalk;
use PSX\Data\Writer;

/**
 * MqBeanstalk
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class MqBeanstalk implements ActionInterface
{
    /**
     * @Inject
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @Inject
     * @var \Fusio\Engine\ConnectorInterface
     */
    protected $connector;

    /**
     * @Inject
     * @var \Fusio\Engine\Response\FactoryInterface
     */
    protected $response;

    public function getName()
    {
        return 'MQ-Beanstalk';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $connection = $this->connector->getConnection($configuration->get('connection'));

        if ($connection instanceof Pheanstalk) {
            $writer = new Writer\Json();
            $body   = $writer->write($request->getBody());

            $connection->useTube($configuration->get('queue'))->put($body);

            return $this->response->build(200, [], [
                'success' => true,
                'message' => 'Push was successful'
            ]);
        } else {
            throw new ConfigurationException('Given connection must be an Beanstalk connection');
        }
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newConnection('connection', 'Connection', 'The Beanstalk connection which should be used'));
        $builder->add($elementFactory->newInput('queue', 'Queue', 'text', 'The name of the queue'));
    }

    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function setConnector(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    public function setResponse(ResponseFactoryInterface $response)
    {
        $this->response = $response;
    }
}
