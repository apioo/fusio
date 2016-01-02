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

use Fusio\Impl\Table\Scope;
use Fusio\Impl\Table\User;
use Fusio\Impl\Service\User as ServiceUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * AddUserCommand
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class AddUserCommand extends Command
{
    protected $userService;

    public function __construct(ServiceUser $userService)
    {
        parent::__construct();

        $this->userService = $userService;
    }

    protected function configure()
    {
        $this
            ->setName('adduser')
            ->setDescription('Adds a new user account');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        // status
        $question = new Question('Choose the status for the account [0=Consumer, 1=Administrator]: ');
        $question->setValidator(function ($value) {
            if (preg_match('/^0|1$/', $value)) {
                return $value;
            } else {
                throw new \Exception('Status must be either 0 or 1');
            }
        });

        $status = $helper->ask($input, $output, $question);

        // username
        $question = new Question('Enter the username of the account: ');
        $question->setValidator(function ($value) {
            if (!preg_match('/^[A-z0-9\-\_\.]{3,32}$/', $value)) {
                throw new \Exception('The username must match the following regexp [A-z0-9\-\_\.]{3,32}');
            }

            return $value;
        });

        $name = $helper->ask($input, $output, $question);

        // scopes
        $question = new Question('Enter a comma seperated list of scopes which should be assigned to the account i.e.: [consumer,authorization]: ');
        $question->setValidator(function ($value) {
            return array_map('trim', explode(',', $value));
        });

        $scopes = $helper->ask($input, $output, $question);

        // password
        $password = $this->userService->create(
            $status,
            $name,
            $scopes
        );

        $output->writeln('Created user ' . $name . ' successful');
        $output->writeln('The following passord was assigned to the account:');
        $output->writeln($password);
    }
}
