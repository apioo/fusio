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

use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\ProcessorInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Impl\Validate\ExpressionFilter;
use Fusio\Impl\Validate\ServiceContainer;
use PSX\Cache;
use PSX\Http\Exception as StatusCode;
use PSX\Validate\Property;
use PSX\Validate\Validator as PSXValidator;
use Symfony\Component\Yaml\Parser;

/**
 * Validator
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Validator implements ActionInterface
{
    /**
     * @Inject
     * @var \Fusio\Engine\ProcessorInterface
     */
    protected $processor;

    /**
     * @Inject
     * @var \Fusio\Impl\Validate\ServiceContainer
     */
    protected $validateServiceContainer;

    /**
     * @Inject
     * @var \PSX\Cache
     */
    protected $cache;

    public function getName()
    {
        return 'Validator';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $yaml  = new Parser();
        $rules = $yaml->parse($configuration->get('rules'));

        if (is_array($rules)) {
            $validator = $this->buildValidator($rules);

            // fragments
            $fragments = $request->getUriFragments();
            foreach ($fragments as $key => $value) {
                $validator->validateProperty('/~path/' . $key, $value);
            }

            // parameters
            $parameters = $request->getParameters();
            foreach ($parameters as $key => $value) {
                $validator->validateProperty('/~query/' . $key, $value);
            }

            // body
            $validator->validate($request->getBody());

            // check whether all required fields are available
            $fields = $validator->getRequiredNames();
            if (!empty($fields)) {
                throw new StatusCode\ClientErrorException('Missing required fields: ' . implode(', ', $fields));
            }
        }

        return $this->processor->execute($configuration->get('action'), $request, $context);
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newAction('action', 'Action', 'Action which gets executed if the validation was successful'));
        $builder->add($elementFactory->newTextArea('rules', 'Rules', 'yaml', 'The validation rules in YAML format. Click <a ng-click="help.showDialog(\'help/action/validator.md\')">here</a> for more informations about the format.'));
    }

    public function setProcessor(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function setValidateServiceContainer(ServiceContainer $serviceContainer)
    {
        $this->validateServiceContainer = $serviceContainer;
    }

    protected function buildValidator(array $rules)
    {
        $fields = array();
        foreach ($rules as $path => $rule) {
            $message  = null;
            $required = false;
            if (is_string($rule)) {
                $expr     = $rule;
            } elseif (is_array($rule)) {
                $expr     = isset($rule['rule'])     ? $rule['rule']     : null;
                $message  = isset($rule['message'])  ? $rule['message']  : null;
                $required = isset($rule['required']) ? $rule['required'] : false;
            }

            if (!empty($expr)) {
                if (empty($message)) {
                    $message = '%s contains an invalid value';
                }

                $fields[] = new Property(
                    $path,
                    null,
                    [new ExpressionFilter($this->validateServiceContainer, $this->cache, $expr, $message)],
                    $required
                );
            }
        }

        return new PSXValidator($fields);
    }
}
