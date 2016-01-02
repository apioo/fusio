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

namespace Fusio\Impl\Service;

use Fusio\Impl\Table\Action as TableAction;
use Fusio\Impl\Table\Routes\Action as TableRoutesAction;
use PSX\Data\ResultSet;
use PSX\DateTime;
use PSX\Http\Exception as StatusCode;
use PSX\Sql;
use PSX\Sql\Condition;

/**
 * Action
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Action
{
    protected $actionTable;
    protected $actionRoutesTable;

    public function __construct(TableAction $actionTable, TableRoutesAction $actionRoutesTable)
    {
        $this->actionTable       = $actionTable;
        $this->actionRoutesTable = $actionRoutesTable;
    }

    public function getAll($startIndex = 0, $search = null, $routeId = null)
    {
        $condition = new Condition();

        if (!empty($search)) {
            $condition->like('name', '%' . $search . '%');
        }

        if (!empty($routeId)) {
            $sql = 'SELECT actionId
                      FROM fusio_routes_action
                     WHERE routeId = ?';

            $condition->raw('id IN (' . $sql . ')', [$routeId]);
        }

        $this->actionTable->setRestrictedFields(['class', 'config']);

        return new ResultSet(
            $this->actionTable->getCount($condition),
            $startIndex,
            16,
            $this->actionTable->getAll($startIndex, 16, 'id', Sql::SORT_DESC, $condition)
        );
    }

    public function get($actionId)
    {
        $action = $this->actionTable->get($actionId);

        if (!empty($action)) {
            return $action;
        } else {
            throw new StatusCode\NotFoundException('Could not find action');
        }
    }

    public function create($name, $class, $config)
    {
        $this->actionTable->create(array(
            'status' => TableAction::STATUS_ACTIVE,
            'name'   => $name,
            'class'  => $class,
            'config' => $config,
            'date'   => new \DateTime(),
        ));
    }

    public function update($actionId, $name, $class, $config)
    {
        $action = $this->actionTable->get($actionId);

        if (!empty($action)) {
            $this->checkLocked($action);

            $this->actionTable->update(array(
                'id'     => $action->getId(),
                'name'   => $name,
                'class'  => $class,
                'config' => $config,
                'date'   => new \DateTime(),
            ));
        } else {
            throw new StatusCode\NotFoundException('Could not find action');
        }
    }

    public function delete($actionId)
    {
        $action = $this->actionTable->get($actionId);

        if (!empty($action)) {
            $this->checkLocked($action);

            // delete route dependencies
            $this->actionRoutesTable->deleteByAction($action['id']);

            $this->actionTable->delete(array(
                'id' => $action['id']
            ));
        } else {
            throw new StatusCode\NotFoundException('Could not find action');
        }
    }

    protected function checkLocked($action)
    {
        if ($action['status'] == TableAction::STATUS_LOCKED) {
            $paths = $this->actionTable->getDependingRoutePaths($action['id']);

            $paths = implode(', ', $paths);

            throw new StatusCode\ConflictException('Action is locked because it is used by a route. Change the route status to "Development" or "Closed" to unlock the schema. The following routes reference this schema: ' . $paths);
        }
    }
}
