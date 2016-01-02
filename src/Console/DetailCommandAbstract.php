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

namespace Fusio\Impl\Console;

use Doctrine\DBAL\Connection;
use Fusio\Engine\ConfigurableInterface;
use Fusio\Engine\Factory\FactoryInterface;
use Fusio\Impl\Form;
use PSX\Data\RecordInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * DetailCommandAbstract
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class DetailCommandAbstract extends Command
{
    protected $factory;
    protected $connection;

    public function __construct(FactoryInterface $factory, Connection $connection)
    {
        parent::__construct();

        $this->factory    = $factory;
        $this->connection = $connection;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $object = $this->factory->factory($input->getArgument('class'));

        if ($object instanceof ConfigurableInterface) {
            $elementFactory = new Form\ElementFactory($this->connection);
            $builder        = new Form\Builder();

            $object->configure($builder, $elementFactory);

            $fields = $builder->getForm()->getRecordInfo();
            $rows   = [];

            foreach ($fields->getField('element') as $element) {
                $type    = substr(strrchr(get_class($element), '\\'), 1);
                $details = $this->getDetails($element);

                if (strlen($details) > 32) {
                    $details = substr($details, 0, 32) . ' [...]';
                }

                $rows[] = [$element->getName(), $type, $details];
            }

            $table = $this->getHelper('table');
            $table
                ->setHeaders(['Name', 'Type', 'Details'])
                ->setRows($rows);

            $table->render($output);
        } else {
            $output->writeln('The object is not configurable');
        }
    }

    protected function getDetails(RecordInterface $element)
    {
        if ($element instanceof Form\Element\Action) {
            return '';
        } elseif ($element instanceof Form\Element\Connection) {
            return '';
        } elseif ($element instanceof Form\Element\Input) {
            return $element->getType();
        } elseif ($element instanceof Form\Element\Select) {
            $options = [];
            foreach ($element->getOptions() as $option) {
                $options[] = $option['key'] . ': ' . $option['value'];
            }
            return implode(', ', $options);
        } elseif ($element instanceof Form\Element\TextArea) {
            return $element->getMode();
        } else {
            return '';
        }
    }
}
