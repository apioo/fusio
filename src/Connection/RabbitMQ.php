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

namespace Fusio\Impl\Connection;

use Fusio\Engine\ConnectionInterface;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;
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
     * @param \Fusio\Engine\ParametersInterface $config
     * @return \MongoDB
     */
    public function getConnection(ParametersInterface $config)
    {
        return new AMQPStreamConnection(
            $config->get('host'),
            $config->get('port'),
            $config->get('user'),
            $config->get('password'),
            $config->get('vhost')
        );
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newInput('host', 'Host', 'text', 'The IP or hostname of the RabbitMQ server'));
        $builder->add($elementFactory->newInput('port', 'Port', 'text', 'The port used to connect to the AMQP broker. The port default is 5672'));
        $builder->add($elementFactory->newInput('user', 'User', 'text', 'The login string used to authenticate with the AMQP broker'));
        $builder->add($elementFactory->newInput('password', 'Password', 'password', 'The password string used to authenticate with the AMQP broker'));
        $builder->add($elementFactory->newInput('vhost', 'VHost', 'text', 'The virtual host to use on the AMQP broker'));
    }
}
