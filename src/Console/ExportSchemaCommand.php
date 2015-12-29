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

namespace Fusio\Impl\Console;

use Doctrine\DBAL\Connection;
use Fusio\Engine\ConfigurableInterface;
use Fusio\Impl\Form;
use PSX\Data\RecordInterface;
use PSX\Data\SchemaInterface;
use PSX\Data\Schema\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ExportSchemaCommand
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ExportSchemaCommand extends Command
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();

        $this->connection = $connection;
    }

    protected function configure()
    {
        $this
            ->setName('export:schema')
            ->setDescription('Returns the complete json schema of a given schema name')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the json schema');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = 'SELECT schema.cache
                  FROM fusio_schema `schema`
                 WHERE schema.name = :name';

        $row = $this->connection->fetchAssoc($sql, array('name' => $input->getArgument('name')));

        if (!empty($row)) {
            $generator = new Generator\JsonSchema();
            $schema    = unserialize($row['cache']);

            if ($schema instanceof SchemaInterface) {
                $output->writeln($generator->generate($schema));
            } else {
                $output->writeln('Invalid schema name');
                return 1;
            }
        } else {
            $output->writeln('Invalid schema name');
            return 1;
        }
    }
}
