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

namespace Fusio\Impl\Backend\Filter\Routes;

use PSX\FilterAbstract;

/**
 * Path
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Path extends FilterAbstract
{
    protected $errorMessage = '%s is not a valid path';

    public function apply($value)
    {
        if (!empty($value)) {
            if (substr($value, 0, 1) != '/') {
                $this->errorMessage = '%s must start with a /';
                return false;
            }

            $parts = explode('/', $value);
            array_shift($parts); // the first part is always empty

            // it is possible to use the root path /
            if (empty($parts)) {
                return true;
            }

            // check reserved segments
            if (in_array($parts[0], $this->getReservedSegments())) {
                $this->errorMessage = '%s uses a path segment which is reserved for the system';
                return false;
            }

            foreach ($parts as $part) {
                if (empty($part)) {
                    $this->errorMessage = '%s has an empty path segment';
                    return false;
                }

                if (!preg_match('/^[A-z0-9\-\.\_\~\!\$\&\\\'\(\)\*\+\,\;\=\:\@]+$/', $part)) {
                    $this->errorMessage = '%s contains invalid characters inside a path segment';
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    protected function getReservedSegments()
    {
        return ['backend', 'doc', 'authorization', 'export'];
    }
}
