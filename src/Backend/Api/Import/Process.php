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

namespace Fusio\Impl\Backend\Api\Import;

use Fusio\Impl\Adapter\Installer;
use Fusio\Impl\Adapter\InstructionParser;
use Fusio\Impl\Authorization\ProtectionTrait;
use PSX\Controller\ApiAbstract;

/**
 * Process
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Process extends ApiAbstract
{
    use ProtectionTrait;

    /**
     * @Inject
     * @var \PSX\Dispatch
     */
    protected $dispatch;

    /**
     * @Inject
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function onPost()
    {
        $installer = new Installer($this->dispatch, $this->connection, $this->logger);
        $parser    = new InstructionParser();

        try {
            $this->connection->beginTransaction();

            $installer->install($parser->parse($this->getBody()));

            $this->connection->commit();

            $this->setBody([
                'success' => true,
                'message' => 'Import successful'
            ]);
        } catch (\Exception $e) {
            $this->connection->rollback();

            throw $e;
        }
    }
}
