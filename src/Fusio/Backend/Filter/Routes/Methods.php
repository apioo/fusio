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

namespace Fusio\Backend\Filter\Routes;

use PSX\FilterAbstract;

/**
 * Methods
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Methods extends FilterAbstract
{
    public function apply($value)
    {
        $methods = explode('|', strtoupper($value));
        if (!empty($methods)) {
            $methods    = array_unique($methods);
            $methodDiff = array_diff($methods, array('GET', 'POST', 'PUT', 'DELETE'));
            if (count($methodDiff) > 0) {
                return false;
            }

            return implode('|', $methods);
        }

        return false;
    }

    public function getErrorMessage()
    {
        return '%s must contain only GET, POST, PUT or DELETE';
    }
}
