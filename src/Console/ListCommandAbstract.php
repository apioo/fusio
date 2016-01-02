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

use Fusio\Engine\Parser\ParserInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ListCommandAbstract
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
abstract class ListCommandAbstract extends Command
{
    protected $parser;

    public function __construct(ParserInterface $parser)
    {
        parent::__construct();

        $this->parser = $parser;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $classes = $this->parser->getClasses();
        $rows    = [];

        foreach ($classes as $row) {
            $rows[] = $row;
        }

        $table = $this->getHelper('table');
        $table
            ->setHeaders(['Name', 'Class'])
            ->setRows($rows);

        $table->render($output);
    }
}
