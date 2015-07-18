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

namespace Fusio\Connection;

use Fusio\ConnectionInterface;
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * RabbitMQ
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class RabbitMQ implements ConnectionInterface
{
    public function getName()
    {
        return 'RabbitMQ';
    }

    /**
     * @param \Fusio\Parameters $config
     * @return \MongoDB
     */
    public function getConnection(Parameters $config)
    {
        return new AMQPStreamConnection(
            $config->get('host'),
            $config->get('port'),
            $config->get('user'),
            $config->get('password'),
            $config->get('vhost')
        );
    }

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Input('host', 'Host', 'text', 'The IP or hostname of the RabbitMQ server'));
        $form->add(new Element\Input('port', 'Port', 'text', 'The port used to connect to the AMQP broker. The port default is 5672'));
        $form->add(new Element\Input('user', 'User', 'text', 'The login string used to authenticate with the AMQP broker'));
        $form->add(new Element\Input('password', 'Password', 'text', 'The password string used to authenticate with the AMQP broker'));
        $form->add(new Element\Input('vhost', 'VHost', 'text', 'The virtual host to use on the AMQP broker'));

        return $form;
    }
}
