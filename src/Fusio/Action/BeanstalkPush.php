<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Action;

use Fusio\ActionInterface;
use Fusio\ConfigurationException;
use Fusio\Context;
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use Fusio\Request;
use Fusio\Response;
use Pheanstalk\Pheanstalk;
use PSX\Data\Writer;

/**
 * BeanstalkPush
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class BeanstalkPush implements ActionInterface
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
        return 'Beanstalk-Push';
    }

    public function handle(Request $request, Parameters $configuration, Context $context)
    {
        $connection = $this->connector->getConnection($configuration->get('connection'));

        if ($connection instanceof Pheanstalk) {
            $writer = new Writer\Json();
            $body   = $writer->write($request->getBody());

            $connection->useTube($configuration->get('queue'))->put($body);

            return new Response(200, [], array(
                'success' => true,
                'message' => 'Push was successful'
            ));
        } else {
            throw new ConfigurationException('Given connection must be an Beanstalk connection');
        }
    }

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Connection('connection', 'Connection', $this->connection, 'The Beanstalk connection which should be used'));
        $form->add(new Element\Input('queue', 'Queue', 'text', 'The name of the queue'));

        return $form;
    }
}
