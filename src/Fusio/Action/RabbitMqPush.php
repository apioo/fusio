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
use Fusio\ConfigurationException;
use Fusio\Connector;
use Fusio\Context;
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use Fusio\Request;
use Fusio\Response;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PSX\Data\Writer;

/**
 * RabbitMqPush
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class RabbitMqPush implements ActionInterface
{
    /**
     * @Inject
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @Inject
     * @var \Fusio\Connector
     */
    protected $connector;

    public function getName()
    {
        return 'RabbitMQ-Push';
    }

    public function handle(Request $request, Parameters $configuration, Context $context)
    {
        $connection = $this->connector->getConnection($configuration->get('connection'));

        if ($connection instanceof AMQPStreamConnection) {
            $writer  = new Writer\Json();
            $body    = $writer->write($request->getBody());

            $message = new AMQPMessage($body, array('content_type' => $writer->getContentType(), 'delivery_mode' => 2));
            $channel = $connection->channel();
            $channel->basic_publish($message, '', $configuration->get('queue'));

            return new Response(200, [], array(
                'success' => true,
                'message' => 'Push was successful'
            ));
        } else {
            throw new ConfigurationException('Given connection must be an AMQP connection');
        }
    }

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Connection('connection', 'Connection', $this->connection, 'The RabbitMQ connection which should be used'));
        $form->add(new Element\Input('queue', 'Queue', 'text', 'The name of the queue'));

        return $form;
    }

    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function setConnector(Connector $connector)
    {
        $this->connector = $connector;
    }
}
