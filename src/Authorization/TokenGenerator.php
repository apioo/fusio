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

namespace Fusio\Impl\Authorization;

use PSX\Util\Uuid;
use RandomLib\Factory;

/**
 * TokenGenerator
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class TokenGenerator
{
    /**
     * Generates the bearer authorization token
     *
     * @return string
     */
    public static function generateToken()
    {
        $factory   = new Factory();
        $generator = $factory->getMediumStrengthGenerator();

        return implode('-', [
            $generator->generateString(20),
            $generator->generateString(48),
            $generator->generateString(10)
        ]);
    }

    /**
     * Generates the authorization code 
     *
     * @return string
     */
    public static function generateCode()
    {
        $factory   = new Factory();
        $generator = $factory->getMediumStrengthGenerator();

        return $generator->generateString(16);
    }

    /**
     * Generates the app key
     *
     * @return string
     */
    public static function generateAppKey()
    {
        return Uuid::pseudoRandom();
    }

    /**
     * Generates the app secret
     *
     * @return string
     */
    public static function generateAppSecret()
    {
        $factory   = new Factory();
        $generator = $factory->getMediumStrengthGenerator();

        return $generator->generateString(64);
    }

    /**
     * Generates the user password
     *
     * @return string
     */
    public static function generateUserPassword()
    {
        $factory   = new Factory();
        $generator = $factory->getMediumStrengthGenerator();

        return $generator->generateString(20);
    }
}
