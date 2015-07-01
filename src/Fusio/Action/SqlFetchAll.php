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

use Doctrine\DBAL\Connection;
use Fusio\ActionInterface;
use Fusio\ConfigurationException;
use Fusio\Context;
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use Fusio\Request;
use Fusio\Response;
use PSX\Util\CurveArray;

/**
 * SqlFetchAll
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class SqlFetchAll implements ActionInterface
{
    /**
     * @Inject
     * @var Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @Inject
     * @var Fusio\Connector
     */
    protected $connector;

    public function getName()
    {
        return 'SQL-Fetch-All';
    }

    public function handle(Request $request, Parameters $configuration, Context $context)
    {
        $connection = $this->connector->getConnection($configuration->get('connection'));

        if ($connection instanceof Connection) {
            $params = array();
            $sql    = $configuration->get('sql');
            $sql    = SqlExecute::substituteParameters($request, $sql, $params);

            $result = $connection->fetchAll($sql, $params);
            $key    = $configuration->get('propertyName') ?: 'entry';

            return new Response(200, [], CurveArray::nest(array(
                $key => $result,
            )));
        } else {
            throw new ConfigurationException('Given connection must be an DBAL connection');
        }
    }

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Connection('connection', 'Connection', $this->connection, 'The SQL connection which should be used'));
        $form->add(new Element\Input('propertyName', 'Property name', 'text', 'The name of the property under which the result should be inserted'));
        $form->add(new Element\TextArea('sql', 'SQL', 'sql', 'The SELECT statment which gets executed. Uri fragments can be used with i.e. <code>!news_id</code> and GET parameters with i.e. <code>:news_id</code>'));

        return $form;
    }
}
