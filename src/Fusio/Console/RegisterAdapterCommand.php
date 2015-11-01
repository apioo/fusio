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

namespace Fusio\Console;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Fusio\Adapter\AdapterInterface;
use Fusio\Adapter\Installer;
use Fusio\Adapter\Instruction;
use Fusio\Adapter\InstructionParser;
use Fusio\Backend\Filter\Routes\Path as PathFilter;
use Psr\Log\LoggerInterface;
use PSX\Dispatch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * RegisterAdapterCommand
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class RegisterAdapterCommand extends Command
{
    protected $connection;
    protected $installer;
    protected $parser;

    public function __construct(Dispatch $dispatch, Connection $connection, LoggerInterface $logger)
    {
        parent::__construct();

        $this->connection = $connection;
        $this->installer  = new Installer($dispatch, $connection, $logger);
        $this->parser     = new InstructionParser();
    }

    protected function configure()
    {
        $this
            ->setName('register')
            ->setDescription('Registers an adapter to the system')
            ->addArgument('class', InputArgument::REQUIRED, 'The absolute name of the adapter class (Acme\Fusio\Adapter)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('class');

        if (class_exists($class)) {
            $adapter = new $class();
            $helper  = $this->getHelper('question');

            if ($adapter instanceof AdapterInterface) {
                // parse definition
                $definition   = $adapter->getDefinition();
                $instructions = $this->parser->parse($definition);
                $rows         = array();
                $hasRoutes    = false;

                foreach ($instructions as $instruction) {
                    $rows[] = [$instruction->getName(), $instruction->getDescription()];

                    if ($instruction instanceof Instruction\Route) {
                        $hasRoutes = true;
                    }
                }

                // show instructions
                $output->writeLn('Loaded definition ' . $definition);
                $output->writeLn('');
                $output->writeLn('The adapter will install the following entries into the system.');

                $table = $this->getHelper('table');
                $table
                    ->setHeaders(['Type', 'Description'])
                    ->setRows($rows);

                $table->render($output);

                // confirm
                $question = new ConfirmationQuestion('Do you want to continue (y/n)? ', false);

                if ($helper->ask($input, $output, $question)) {
                    // if the adapter installs new routes ask for a base path
                    if ($hasRoutes) {
                        $output->writeLn('');
                        $output->writeLn('The adapter inserts new routes into the system.');
                        $output->writeLn('Please specify a base path under which the new routes are inserted.');

                        $filter   = new PathFilter();
                        $question = new Question('Base path (i.e. /acme/service): ', '/');
                        $question->setValidator(function ($answer) use ($filter) {

                            if (!$filter->apply($answer)) {
                                throw new \RuntimeException(sprintf($filter->getErrorMessage(), 'Base path'));
                            }

                            return $answer;

                        });

                        $basePath = $helper->ask($input, $output, $question);
                    }

                    try {
                        $this->connection->beginTransaction();

                        $this->installer->install($instructions, $basePath);

                        $this->connection->commit();

                        $output->writeln('Registration successful');
                    } catch(\Exception $e) {
                        $this->connection->rollback();

                        $output->writeln('An exception occured during installation of the adapter. No changes are applied to the database.');
                        $output->writeln('');
                        $output->writeln('Message: ' . $e->getMessage());
                        $output->writeln('Trace: ' . $e->getTraceAsString());
                    }
                } else {
                    $output->writeln('Abort');
                }
            } else {
                $output->writeln('Class does not implement the AdapterInterface');
            }
        } else {
            $output->writeln('Provided adapter class does not exist');
        }
    }
}
