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

namespace Fusio\Impl\Parser;

use Doctrine\DBAL\Connection;
use Fusio\Engine\ConfigurableInterface;
use Fusio\Engine\Factory\FactoryInterface;
use Fusio\Engine\Parser\ParserInterface;
use Fusio\Impl\Form;

/**
 * ParserAbstract
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
abstract class ParserAbstract implements ParserInterface
{
    protected $factory;
    protected $connection;

    public function __construct(FactoryInterface $factory, Connection $connection)
    {
        $this->factory    = $factory;
        $this->connection = $connection;
    }

    public function getForm($className)
    {
        $object = $this->getObject($className);

        if ($object instanceof ConfigurableInterface) {
            $elementFactory = new Form\ElementFactory($this->connection);
            $builder        = new Form\Builder();

            $object->configure($builder, $elementFactory);

            return $builder->getForm();
        }

        return null;
    }

    protected function getObject($className)
    {
        if (empty($className) || !is_string($className)) {
            throw new \RuntimeException('Invalid class name');
        }

        if (!class_exists($className)) {
            return null;
        }

        return $this->factory->factory($className);
    }
}
