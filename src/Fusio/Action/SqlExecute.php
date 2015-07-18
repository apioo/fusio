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
use Fusio\Template\Parser;
use PSX\Validate;

/**
 * SqlExecute
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SqlExecute implements ActionInterface
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

    /**
     * @Inject
     * @var \Fusio\Template\Parser
     */
    protected $templateParser;

    public function getName()
    {
        return 'SQL-Execute';
    }

    public function handle(Request $request, Parameters $configuration, Context $context)
    {
        $connection = $this->connector->getConnection($configuration->get('connection'));

        if ($connection instanceof Connection) {
            // parse sql
            $sql = $this->templateParser->parse($request, $configuration, $context, $configuration->get('sql'));

            $connection->executeUpdate($sql, $this->templateParser->getSqlParameters());

            return new Response(200, [], array(
                'success' => true,
                'message' => 'Execution was successful'
            ));
        } else {
            throw new ConfigurationException('Given connection must be an DBAL connection');
        }
    }

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Connection('connection', 'Connection', $this->connection, 'The SQL connection which should be used'));
        $form->add(new Element\TextArea('sql', 'SQL', 'sql', 'The INSERT, UPDATE or DELETE query which gets executed. It is possible to access values from the environment with i.e. <code ng-non-bindable>{{ body.get("title")|prepare }}</code>. <b>Note you must use the prepare filter for each parameter in order to generate a safe SQL query which uses prepared statments.</b> Click <a ng-click="help.showDialog(\'help/template.md\')">here</a> for more informations about the template syntax.'));

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

    public function setTemplateParser(Parser $templateParser)
    {
        $this->templateParser = $templateParser;
    }
}
