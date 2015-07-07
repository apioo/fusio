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
use MongoClient;

/**
 * MongoDB
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class MongoDB implements ConnectionInterface
{
    public function getName()
    {
        return 'Mongo-DB';
    }

    /**
     * @param \Fusio\Parameters $config
     * @return \MongoDB
     */
    public function getConnection(Parameters $config)
    {
        $client = new MongoClient($config->get('url'));

        return $client->selectDB($config->get('database'));
    }

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Input('url', 'Url'));
        $form->add(new Element\Input('database', 'Database'));

        return $form;
    }
}
