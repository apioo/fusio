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

namespace Fusio\Impl\Dependency;

use Fusio\Impl;

/**
 * Service
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
trait Service
{
    /**
     * @return \Fusio\Impl\Service\User
     */
    public function getUserService()
    {
        return new Impl\Service\User(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\User'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Scope'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\App'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\User\Scope')
        );
    }

    /**
     * @return \Fusio\Impl\Service\Routes
     */
    public function getRoutesService()
    {
        return new Impl\Service\Routes(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Routes'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Scope\Route'),
            $this->get('routes_dependency_manager')
        );
    }

    /**
     * @return \Fusio\Impl\Service\Action
     */
    public function getActionService()
    {
        return new Impl\Service\Action(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Action'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Routes\Action')
        );
    }

    /**
     * @return \Fusio\Impl\Service\App
     */
    public function getAppService()
    {
        return new Impl\Service\App(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\App'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Scope'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\App\Scope'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\User\Scope'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\App\Token')
        );
    }

    /**
     * @return \Fusio\Impl\Service\App\Developer
     */
    public function getAppDeveloperService()
    {
        return new Impl\Service\App\Developer(
            $this->get('app_service'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\App'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Scope'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\User\Scope'),
            $this->get('config')->get('fusio_app_per_consumer'),
            $this->get('config')->get('fusio_app_approval')
        );
    }

    /**
     * @return \Fusio\Impl\Service\App\Grant
     */
    public function getAppGrantService()
    {
        return new Impl\Service\App\Grant(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\App'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\User\Grant'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\App\Token')
        );
    }

    /**
     * @return \Fusio\Impl\Service\Connection
     */
    public function getConnectionService()
    {
        return new Impl\Service\Connection(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Connection')
        );
    }

    /**
     * @return \Fusio\Impl\Service\Dashboard
     */
    public function getDashboardService()
    {
        return new Impl\Service\Dashboard(
            $this->get('connection')
        );
    }

    /**
     * @return \Fusio\Impl\Service\Log
     */
    public function getLogService()
    {
        return new Impl\Service\Log(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Log')
        );
    }

    /**
     * @return \Fusio\Impl\Service\Statistic
     */
    public function getStatisticService()
    {
        return new Impl\Service\Statistic(
            $this->get('connection')
        );
    }

    /**
     * @return \Fusio\Impl\Service\Schema
     */
    public function getSchemaService()
    {
        return new Impl\Service\Schema(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Schema'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Routes\Schema'),
            $this->get('schema_parser')
        );
    }

    /**
     * @return \Fusio\Impl\Service\Scope
     */
    public function getScopeService()
    {
        return new Impl\Service\Scope(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Scope'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Scope\Route'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\App\Scope'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\User\Scope')
        );
    }

    /**
     * @return \Fusio\Impl\Service\Routes\DependencyManager
     */
    public function getRoutesDependencyManager()
    {
        return new Impl\Service\Routes\DependencyManager(
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Schema'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Routes\Schema'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Action'),
            $this->get('table_manager')->getTable('Fusio\Impl\Backend\Table\Routes\Action'),
            $this->get('action_parser')
        );
    }
}
