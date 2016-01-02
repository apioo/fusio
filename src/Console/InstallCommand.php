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
use Doctrine\DBAL\Schema\Schema;
use Fusio\Impl\Base;
use Fusio\Impl\Database\Installer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * InstallCommand
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class InstallCommand extends Command
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
            ->setName('install')
            ->setDescription('Installs the schema to the database defined in the coniguration file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fromSchema = $this->connection->getSchemaManager()->createSchema();
        $tableCount = count($fromSchema->getTableNames());

        if ($tableCount > 0) {
            $helper   = $this->getHelper('question');
            $question = new ConfirmationQuestion('The provided database "' . $fromSchema->getName() . '" contains already ' . $tableCount . ' tables.' . "\n" . 'The installation script will DELETE all tables on the database which does not belong to the fusio schema.' . "\n" . 'Do you want to continue with this action (y|n)?', false);

            if (!$helper->ask($input, $output, $question)) {
                return;
            }
        }

        // execute install or upgrade
        $currentVersion = $this->getInstalledVersion($fromSchema);
        $installer      = new Installer($this->connection);

        if ($currentVersion !== null) {
            $output->writeln('Upgrade from version ' . $currentVersion . ' to ' . Base::getVersion());

            $installer->upgrade($currentVersion, Base::getVersion());

            $output->writeln('Upgrade successful');
        } else {
            $output->writeln('Install version ' . Base::getVersion());

            $installer->install(Base::getVersion());

            $output->writeln('Installation successful');
        }
    }

    protected function getInstalledVersion(Schema $schema)
    {
        if ($schema->hasTable('fusio_meta')) {
            $version = $this->connection->fetchColumn('SELECT version FROM fusio_meta ORDER BY installDate DESC, id DESC LIMIT 1');
            if (!empty($version)) {
                return $version;
            }
        }

        return null;
    }
}
