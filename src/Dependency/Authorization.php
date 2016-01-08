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

namespace Fusio\Impl\Dependency;

use Fusio\Impl\Authorization as ApiAuthorization;
use Fusio\Impl\Backend\Authorization as BackendAuthorization;
use Fusio\Impl\Consumer\Authorization as ConsumerAuthorization;
use PSX\Oauth2\Provider\GrantTypeFactory;

/**
 * Authorization
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
trait Authorization
{
    /**
     * @return \PSX\Oauth2\Provider\GrantTypeFactory
     */
    public function getApiGrantTypeFactory()
    {
        $factory = new GrantTypeFactory();

        $factory->add(new ApiAuthorization\Password(
            $this->get('app_service'),
            $this->get('scope_service'),
            $this->get('user_service'),
            $this->get('config')->get('fusio_expire_app')
        ));

        $factory->add(new ApiAuthorization\AuthorizationCode(
            $this->get('app_code_service'),
            $this->get('scope_service'),
            $this->get('app_service'),
            $this->get('config')->get('fusio_expire_app')
        ));

        return $factory;
    }

    /**
     * @return \PSX\Oauth2\Provider\GrantTypeFactory
     */
    public function getBackendGrantTypeFactory()
    {
        $factory = new GrantTypeFactory();
        $factory->add(new BackendAuthorization\ClientCredentials(
            $this->get('user_service'),
            $this->get('app_service'),
            $this->get('config')->get('fusio_expire_backend')
        ));

        return $factory;
    }

    /**
     * @return \PSX\Oauth2\Provider\GrantTypeFactory
     */
    public function getConsumerGrantTypeFactory()
    {
        $factory = new GrantTypeFactory();
        $factory->add(new ConsumerAuthorization\ClientCredentials(
            $this->get('user_service'),
            $this->get('app_service'),
            $this->get('config')->get('fusio_expire_consumer')
        ));

        return $factory;
    }
}
