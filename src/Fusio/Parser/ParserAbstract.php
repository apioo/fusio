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

namespace Fusio\Parser;

use DirectoryIterator;
use Fusio\ConfigurableInterface;
use Fusio\Factory\FactoryInterface;

/**
 * ParserAbstract
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
abstract class ParserAbstract
{
    protected $factory;
    protected $classes;
    protected $instanceOf;

    public function __construct(FactoryInterface $factory, array $classes, $instanceOf)
    {
        $this->factory    = $factory;
        $this->classes    = $classes;
        $this->instanceOf = $instanceOf;
    }

    public function getClasses()
    {
        $result = array();

        foreach ($this->classes as $class) {
            $object     = $this->getClass($class);
            $instanceOf = $this->instanceOf;

            if ($object instanceof $instanceOf) {
                $result[] = array(
                    'name'  => $object->getName(),
                    'class' => $class,
                );
            }
        }

        return $result;
    }

    public function getForm($className)
    {
        $object = $this->getClass($className);

        if ($object instanceof ConfigurableInterface) {
            return $object->getForm();
        }

        return null;
    }

    public function getClass($className)
    {
        if (empty($className) || !is_string($className)) {
            throw new \RuntimeException('Invalid class name');
        }

        return $this->factory->factory($className);
    }
}
