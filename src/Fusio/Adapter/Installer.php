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

namespace Fusio\Adapter;

use DateTime;
use Doctrine\DBAL\Connection;
use Fusio\Authorization\TokenGenerator;
use Fusio\Base;
use Psr\Log\LoggerInterface;
use PSX\Dispatch;
use PSX\Json;
use PSX\Url;
use PSX\Http\PostRequest;
use PSX\Http\Response;
use PSX\Http\Stream\TempStream;
use ReflectionClass;
use RuntimeException;

/**
 * The installer inserts only the action and connection classes through the 
 * database connection. All other entries are inserted through the API endpoint
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Installer
{
    protected $dispatch;
    protected $connection;
    protected $logger;

    protected $accessToken;

    public function __construct(Dispatch $dispatch, Connection $connection, LoggerInterface $logger)
    {
        $this->dispatch   = $dispatch;
        $this->connection = $connection;
        $this->logger     = $logger;
    }

    public function install(array $instructions, $basePath = null)
    {
        $this->installActionClass($instructions);
        $this->installConnectionClass($instructions);

        $this->installConnection($instructions);
        $this->installSchema($instructions);
        $this->installAction($instructions);
        $this->installRoute($instructions, $basePath);
    }

    protected function installActionClass(array $instructions)
    {
        $instructions = $this->getInstructionByType($instructions, 'Fusio\Adapter\Instruction\ActionClass');
        foreach ($instructions as $instruction) {
            $this->registerClass('fusio_action_class', $instruction->getPayload(), 'Fusio\ActionInterface');
        }
    }

    protected function installConnectionClass(array $instructions)
    {
        $instructions = $this->getInstructionByType($instructions, 'Fusio\Adapter\Instruction\ConnectionClass');
        foreach ($instructions as $instruction) {
            $this->registerClass('fusio_connection_class', $instruction->getPayload(), 'Fusio\ConnectionInterface');
        }
    }

    protected function installConnection(array $instructions)
    {
        $instructions = $this->getInstructionByType($instructions, 'Fusio\Adapter\Instruction\Connection');
        foreach ($instructions as $instruction) {
            $this->submitData($instruction, 'backend/connection');
        }
    }

    protected function installSchema(array $instructions)
    {
        $instructions = $this->getInstructionByType($instructions, 'Fusio\Adapter\Instruction\Schema');
        foreach ($instructions as $instruction) {
            $this->submitData($instruction, 'backend/schema');
        }
    }

    protected function installAction(array $instructions)
    {
        $instructions = $this->getInstructionByType($instructions, 'Fusio\Adapter\Instruction\Action');
        foreach ($instructions as $instruction) {
            $this->submitData($instruction, 'backend/action');
        }
    }

    protected function installRoute(array $instructions, $basePath)
    {
        $instructions = $this->getInstructionByType($instructions, 'Fusio\Adapter\Instruction\Route');
        foreach ($instructions as $instruction) {
            if (!empty($basePath)) {
                $instruction->setBasePath($basePath);
            }

            $this->submitData($instruction, 'backend/routes');
        }
    }

    protected function getInstructionByType(array $instructions, $type)
    {
        return array_filter($instructions, function($value) use ($type) {
            return $value instanceof $type;
        });
    }

    protected function registerClass($tableName, $className, $interface)
    {
        if (!is_string($className)) {
            throw new RuntimeException('Class name must be a string ' . gettype($className) . ' given');
        }

        $class = new ReflectionClass($className);

        if ($class->implementsInterface($interface)) {
            $id = $this->connection->fetchColumn('SELECT id FROM ' . $tableName . ' WHERE class = :class', [
                'class' => $class->getName(),
            ]);

            if (empty($id)) {
                $this->connection->insert($tableName, [
                    'class' => $class->getName(),
                ]);
            } else {
                $this->logger->notice('Class ' . $class->getName() . ' already registered');
            }
        } else {
            throw new RuntimeException('Class ' . $class->getName() . ' must implement the interface ' . $interface);
        }
    }

    protected function submitData(InstructionAbstract $instruction, $endpoint)
    {
        $header   = ['User-Agent' => 'Fusio-Installer v' . Base::getVersion(), 'Authorization' => 'Bearer ' . $this->getAccessToken()];
        $body     = Json::encode($this->substituteParameters($instruction->getPayload()));
        $request  = new PostRequest(new Url('http://127.0.0.1/' . $endpoint), $header, $body);
        $response = new Response();
        $response->setBody(new TempStream(fopen('php://memory', 'r+')));

        $this->dispatch->route($request, $response);

        $body = (string) $response->getBody();
        $data = Json::decode($body, false);

        if (isset($data->success) && $data->success === true) {
            // installation successful
            $message = isset($data->message) ? $data->message : 'Insert ' . $instruction->getName() . ' successful';

            $this->logger->notice($message);
        } else {
            $message = isset($data->message) ? $data->message : 'Unknown error occured';

            throw new RuntimeException($message);
        }
    }

    protected function getAccessToken()
    {
        if (empty($this->accessToken)) {
            // insert access token
            $token  = TokenGenerator::generateToken();
            $expire = new DateTime('+30 minute');
            $now    = new DateTime();

            $this->connection->insert('fusio_app_token', [
                'appId'  => 1,
                'userId' => 1,
                'status' => 1,
                'token'  => $token,
                'scope'  => 'backend',
                'ip'     => '127.0.0.1',
                'expire' => $expire->format('Y-m-d H:i:s'),
                'date'   => $now->format('Y-m-d H:i:s'),
            ]);

            return $this->accessToken = $token;
        } else {
            return $this->accessToken;
        }
    }

    protected function substituteParameters(\stdClass $payload)
    {
        $data = json_encode($payload);
        $data = preg_replace_callback('/\$\{([A-z0-9\-]+)\.([A-z0-9\-]+)\}/', function($matches){

            $type  = isset($matches[1]) ? $matches[1] : null;
            $id    = isset($matches[2]) ? $matches[2] : null;
            $value = $id;

            switch ($type) {
                case 'action':
                    $value = $this->connection->fetchColumn('SELECT id FROM fusio_action WHERE name = :name', [
                        'name' => $id
                    ]);
                    break;

                case 'connection':
                    $value = $this->connection->fetchColumn('SELECT id FROM fusio_connection WHERE name = :name', [
                        'name' => $id
                    ]);
                    break;

                case 'schema':
                    $value = $this->connection->fetchColumn('SELECT id FROM fusio_schema WHERE name = :name', [
                        'name' => $id
                    ]);
                    break;

                default:
                    throw new RuntimeException('Invalid type identifier "' . $type . '"');
            }

            if (empty($value)) {
                throw new RuntimeException(ucfirst($type) . ' with the id "' . $id . '" does not exist');
            }

            return $value;

        }, $data);

        return json_decode($data, JSON_PRETTY_PRINT);
    }
}
