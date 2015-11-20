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

namespace Fusio\Impl\Action;

use Doctrine\DBAL\Connection;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\ProcessorInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Impl\Model\Action;
use Fusio\Impl\Processor\MemoryRepository;
use PSX\Data\Record\Transformer;
use Symfony\Component\Yaml\Parser;

/**
 * Processor
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Processor implements ActionInterface
{
    /**
     * @Inject
     * @var \Fusio\Engine\ProcessorInterface
     */
    protected $processor;

    public function getName()
    {
        return 'Processor';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $yaml       = new Parser();
        $process    = $yaml->parse($configuration->get('process'));
        $repository = new MemoryRepository();
        $id         = 1;

        if (is_array($process)) {
            foreach ($process as $class => $config) {
                if (is_array($config)) {
                    $config = array_map('strval', $config);

                    if (isset($config['id'])) {
                        $name = $config['id'];
                        unset($config['id']);
                    } else {
                        $name = 'action-' . $id;
                    }

                    $action = new Action();
                    $action->setId($id);
                    $action->setName($name);
                    $action->setClass($class);
                    $action->setConfig($config);
                    $action->setDate(date('Y-m-d H:i:s'));

                    $repository->add($action);

                    $id++;
                }
            }
        }

        if ($id === 1) {
            throw new ConfigurationException('No process defined');
        }

        $this->processor->push($repository);

        try {
            $return = $this->processor->execute(1, $request, $context);

            $this->processor->pop();

            return $return;
        } catch (\Exception $e) {
            $this->processor->pop();

            throw $e;
        }
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newTextArea('process', 'Process', 'yaml', 'Executes the described process'));
    }

    public function setProcessor(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }
}
