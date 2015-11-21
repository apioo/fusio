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

namespace Fusio\Impl\Backend\Table\Routes;

use PSX\Api\Resource;
use Fusio\Impl\Backend\Table\Action as TableAction;
use Fusio\Impl\Backend\Table\Routes\Action as RoutesAction;
use Fusio\Impl\Backend\Table\Schema as TableSchema;
use Fusio\Impl\Backend\Table\Routes\Schema as RoutesSchema;
use Fusio\Impl\Backend\Table\Routes;
use Fusio\Impl\Form;
use Fusio\Impl\Parser\ParserAbstract;

/**
 * The dependency manager inserts all schema and action ids which are used by
 * a route. If the route is in production status all dependencies are locked so
 * that they can not change. The locking is somewhat complicated so this manager
 * encapsulate all the logic
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class DependencyManager
{
    protected $schemaTable;
    protected $schemaLinkTable;
    protected $actionTable;
    protected $actionLinkTable;
    protected $actionParser;

    public function __construct(TableSchema $schemaTable, RoutesSchema $schemaLinkTable, TableAction $actionTable, RoutesAction $actionLinkTable, ParserAbstract $actionParser)
    {
        $this->schemaTable     = $schemaTable;
        $this->schemaLinkTable = $schemaLinkTable;
        $this->actionTable     = $actionTable;
        $this->actionLinkTable = $actionLinkTable;
        $this->actionParser    = $actionParser;
    }

    /**
     * Removes all existing dependency links
     *
     * @param integer $routeId
     */
    public function removeExistingDependencyLinks($routeId)
    {
        $this->schemaLinkTable->deleteAllFromRoute($routeId);
        $this->actionLinkTable->deleteAllFromRoute($routeId);
    }

    /**
     * Unlocks all existing dependencies if no other dependency exist
     *
     * @param integer $routeId
     */
    public function unlockExistingDependencies($routeId)
    {
        $schemas = $this->schemaLinkTable->getByRouteId($routeId);
        foreach ($schemas as $schema) {
            $condition = new Condition();
            $condition->equals('schemaId', $schema->getId());
            $condition->equals('status', RoutesSchema::STATUS_REQUIRED);

            $count = $this->schemaLinkTable->getCount($condition);
            if ($count == 0) {
                $this->schemaTable->update([
                    'id'     => $schema->getId(),
                    'status' => Schema::STATUS_ACTIVE,
                ]);
            }
        }

        $actions = $this->actionLinkTable->getByRouteId($routeId);
        foreach ($actions as $action) {
            $condition = new Condition();
            $condition->equals('actionId', $action->getId());
            $condition->equals('status', RoutesAction::STATUS_REQUIRED);

            $count = $this->actionLinkTable->getCount($condition);
            if ($count == 0) {
                $this->actionTable->update([
                    'id'     => $action->getId(),
                    'status' => Action::STATUS_ACTIVE,
                ]);
            }
        }
    }

    /**
     * Inserts the new schema and action dependency links
     *
     * @param integer $routeId
     * @param array $config
     */
    public function insertDependencyLinks($routeId, array $config)
    {
        // get dependencies of the config
        $schemas = $this->getDependingSchemas($config);
        $actions = $this->getDependingActions($config);

        foreach ($schemas as $schemaId => $status) {
            $this->schemaLinkTable->create(array(
                'routeId'  => $routeId,
                'schemaId' => $schemaId,
                'status'   => $status,
            ));
        }

        foreach ($actions as $actionId => $status) {
            $this->actionLinkTable->create(array(
                'routeId'  => $routeId,
                'actionId' => $actionId,
                'status'   => $status,
            ));
        }
    }

    /**
     * Locks all required dependencies
     *
     * @param array $schemas
     * @param array $actions
     */
    public function lockExistingDependencies($routeId)
    {
        $schemas = $this->schemaLinkTable->getByRouteId($routeId);
        foreach ($schemas as $schema) {
            if ($schema->getStatus() == RoutesSchema::STATUS_REQUIRED) {
                $this->schemaTable->update([
                    'id'     => $schema->getSchemaId(),
                    'status' => Schema::STATUS_LOCKED,
                ]);
            }
        }

        $actions = $this->actionLinkTable->getByRouteId($routeId);
        foreach ($actions as $action) {
            if ($action->getStatus() == RoutesAction::STATUS_REQUIRED) {
                $this->actionTable->update([
                    'id'     => $action->getActionId(),
                    'status' => Action::STATUS_LOCKED,
                ]);
            }
        }
    }

    /**
     * Returns all schema ids which are required by the config
     *
     * @return array
     */
    protected function getDependingSchemas(array $config)
    {
        $schemas = [];
        foreach ($config as $version) {
            if ($version->getActive()) {
                foreach ($version->getMethods() as $method) {
                    if ($method->getActive()) {
                        $schemaIds = [];

                        if (is_int($method->getRequest())) {
                            $schemaIds[] = $method->getRequest();
                        }

                        if (is_int($method->getResponse())) {
                            $schemaIds[] = $method->getResponse();
                        }

                        foreach ($schemaIds as $schemaId) {
                            if (in_array($version->getStatus(), [Resource::STATUS_ACTIVE, Resource::STATUS_DEPRECATED])) {
                                $status = RoutesSchema::STATUS_REQUIRED;
                            } else {
                                $status = RoutesSchema::STATUS_OPTIONAL;
                            }

                            if (isset($schemas[$schemaId])) {
                                $existingStatus = $schemas[$schemaId];

                                if ($status == RoutesSchema::STATUS_REQUIRED || $existingStatus == RoutesSchema::STATUS_REQUIRED) {
                                    $schemas[$schemaId] = RoutesSchema::STATUS_REQUIRED;
                                } else {
                                    $schemas[$schemaId] = RoutesSchema::STATUS_OPTIONAL;
                                }
                            } else {
                                $schemas[$schemaId] = $status;
                            }
                        }
                    }
                }
            }
        }

        return $schemas;
    }

    /**
     * Returns all action ids which are required by the config. Resolves also 
     * action ids which depend on another action
     *
     * @return array
     */
    protected function getDependingActions(array $config)
    {
        $actions = [];
        foreach ($config as $version) {
            if ($version->getActive()) {
                foreach ($version->getMethods() as $method) {
                    if ($method->getActive()) {
                        if (is_int($method->getAction())) {
                            $actionId = $method->getAction();

                            if (in_array($version->getStatus(), [Resource::STATUS_ACTIVE, Resource::STATUS_DEPRECATED])) {
                                $status = RoutesAction::STATUS_REQUIRED;
                            } else {
                                $status = RoutesAction::STATUS_OPTIONAL;
                            }

                            if (isset($actions[$actionId])) {
                                $existingStatus = $actions[$actionId];

                                if ($status == RoutesAction::STATUS_REQUIRED || $existingStatus == RoutesAction::STATUS_REQUIRED) {
                                    $actions[$actionId] = RoutesAction::STATUS_REQUIRED;
                                } else {
                                    $actions[$actionId] = RoutesAction::STATUS_OPTIONAL;
                                }
                            } else {
                                $actions[$actionId] = $status;
                            }
                        }
                    }
                }
            }
        }

        return $this->resolveDependingActions($actions);
    }

    protected function resolveDependingActions(array $actions)
    {
        $result = [];
        foreach ($actions as $actionId => $status) {
            // add self action
            $result[$actionId] = $status;

            // add depending actions
            $dependingActions = $this->getDependingActionsByAction($actionId);
            if (count($dependingActions) > 0) {
                foreach ($dependingActions as $depActionId) {
                    if (isset($result[$depActionId])) {
                        $existingStatus = $result[$depActionId];

                        if ($status == RoutesAction::STATUS_REQUIRED || $existingStatus == RoutesAction::STATUS_REQUIRED) {
                            $result[$depActionId] = RoutesAction::STATUS_REQUIRED;
                        } else {
                            $result[$depActionId] = RoutesAction::STATUS_OPTIONAL;
                        }
                    } else {
                        $result[$depActionId] = $status;
                    }
                }
            }
        }

        return $result;
    }

    protected function getDependingActionsByAction($actionId)
    {
        $action  = $this->actionTable->get($actionId);
        $config  = $action->getConfig();
        $form    = $this->actionParser->getForm($action->getClass());
        $actions = [];

        if ($form instanceof Form\Container) {
            foreach ($form as $element) {
                if ($element instanceof Form\Element\Action) {
                    $name = $element->getName();
                    if (isset($config[$name]) && is_int($config[$name])) {
                        $actions[] = $config[$name];

                        $actions = array_merge($actions, $this->getDependingActionsByAction($config[$name]));
                    }
                }
            }
        }

        return array_unique($actions);
    }
}
