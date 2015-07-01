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
use PSX\Data\Accessor;
use PSX\Data\RecordInterface;
use PSX\Validate;

/**
 * SqlExecute
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class SqlExecute implements ActionInterface
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
        return 'SQL-Execute';
    }

    public function handle(Request $request, Parameters $configuration, Context $context)
    {
        $connection = $this->connector->getConnection($configuration->get('connection'));

        if ($connection instanceof Connection) {
            $params = array();
            $sql    = $configuration->get('sql');
            $sql    = self::substituteParameters($request, $sql, $params, true);

            $connection->execute($sql, $params);

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
        $form->add(new Element\TextArea('sql', 'SQL', 'sql', 'The INSERT, UPDATE or DELETE query which gets executed. Uri fragments can be used with i.e. <code>!news_id</code> and GET parameters with i.e. <code>:news_id</code>. The body data can be accessed with i.e. <code>#author.name</code>'));

        return $form;
    }

    public static function substituteParameters(Request $request, $sql, array &$params, $withBodyParameters = false)
    {
        preg_match_all('/(\#|\:|\!)([A-z0-9\-\_\/]+)/', $sql, $matches);

        $types    = isset($matches[1]) ? $matches[1] : array();
        $keys     = isset($matches[2]) ? $matches[2] : array();
        $params   = array();
        $accessor = new Accessor(new Validate(), $request->getBody());

        foreach ($keys as $index => $key) {
            $sql   = str_replace($types[$index] . $key, '?', $sql);
            $value = null;

            if ($types[$index] == '!') {
                $value = $request->getUriFragment($key) ?: null;
            } elseif ($types[$index] == ':') {
                $value = $request->getParameter($key) ?: null;
            } elseif ($types[$index] == '#') {
                if ($withBodyParameters) {
                    $value = $accessor->get($name) ?: null;
                } else {
                    $value = null;
                }
            }

            if ($value instanceof RecordInterface || $value instanceof \stdClass || is_array($value)) {
                $value = serialize($value);
            }

            $params[$index] = $value;
        }

        return $sql;
    }
}
