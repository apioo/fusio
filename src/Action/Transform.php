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
use Fusio\Engine\Template\FactoryInterface;
use Fusio\Impl\Validate\ExpressionFilter;
use Fusio\Impl\Validate\ServiceContainer;
use PSX\Cache;
use PSX\Data\Record;
use PSX\Data\Record\Transformer;
use PSX\Http\Exception as StatusCode;
use PSX\Json;
use PSX\Json\Patch;
use PSX\Validate\Property;
use PSX\Validate\Validator as PSXValidator;
use Symfony\Component\Yaml\Parser;

/**
 * Transform
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Transform implements ActionInterface
{
    /**
     * @Inject
     * @var \Fusio\Engine\Template\FactoryInterface
     */
    protected $templateFactory;

    /**
     * @Inject
     * @var \Fusio\Engine\ProcessorInterface
     */
    protected $processor;

    public function getName()
    {
        return 'Transform';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        // parse json
        $parser   = $this->templateFactory->newTextParser();
        $response = $parser->parse($request, $context, $configuration->get('patch'));

        // patch
        $patch = new Patch(Json::decode($response, false));
        $body  = $patch->patch(Transformer::toStdClass($request->getBody()));
        $body  = Transformer::toRecord($body);

        return $this->processor->execute($configuration->get('action'), $request->withBody($body), $context);
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newAction('action', 'Action', 'Action which gets executed after the transformation'));
        $builder->add($elementFactory->newTextArea('patch', 'Patch', 'json', 'JSON Patch operations which are applied to the request body. More informations about the JSON Patch format at <a href="https://tools.ietf.org/html/rfc6902">https://tools.ietf.org/html/rfc6902</a>'));
    }

    public function setTemplateFactory(FactoryInterface $templateFactory)
    {
        $this->templateFactory = $templateFactory;
    }

    public function setProcessor(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }
}
