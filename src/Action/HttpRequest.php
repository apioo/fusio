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
use Fusio\Engine\RequestInterface;
use Fusio\Engine\ResponseInterface;
use Fusio\Engine\Response\FactoryInterface as ResponseFactoryInterface;
use Fusio\Engine\Template\FactoryInterface;
use Fusio\Impl\Base;
use PSX\Http;
use PSX\Http\Request;
use PSX\Url;
use Symfony\Component\Yaml\Parser;

/**
 * HttpRequest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class HttpRequest implements ActionInterface
{
    /**
     * @Inject
     * @var \PSX\Http
     */
    protected $http;

    /**
     * @Inject
     * @var \Fusio\Template\FactoryInterface
     */
    protected $templateFactory;

    /**
     * @Inject
     * @var \Fusio\Response\FactoryInterface
     */
    protected $response;

    public function getName()
    {
        return 'HTTP-Request';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $response = $this->executeRequest($request, $configuration, $context);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $this->response->build(200, [], [
                'success' => true,
                'message' => 'Request successful'
            ]);
        } else {
            return $this->response->build(500, [], [
                'success' => false,
                'message' => 'Request failed'
            ]);
        }
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        $methods = array_combine($methods, $methods);

        $builder->add($elementFactory->newInput('url', 'Url', 'text', 'Sends a HTTP request to the given url'));
        $builder->add($elementFactory->newSelect('method', 'Method', $methods, 'The used request method'));
        //$builder->add($elementFactory->newTextArea('headers', 'Headers', 'yaml', 'Optional the '));
        $builder->add($elementFactory->newTextArea('body', 'Body', 'text', 'The request body. Inside the body it is possible to use a template syntax to add dynamic data. Click <a ng-click="help.showDialog(\'help/template.md\')">here</a> for more informations about the template syntax.'));
    }

    public function setHttp(Http $http)
    {
        $this->http = $http;
    }

    public function setTemplateFactory(FactoryInterface $templateFactory)
    {
        $this->templateFactory = $templateFactory;
    }

    public function setResponse(ResponseFactoryInterface $response)
    {
        $this->response = $response;
    }

    protected function parserHeaders($headers)
    {
        $result  = [];
        $yaml    = new Parser();
        $headers = $yaml->parse($headers);

        if (is_array($headers)) {
            foreach ($headers as $key => $value) {
                if (is_string($key) && is_string($value)) {
                    $result[$key] = $value;
                }
            }
        }

        // set user agent
        $headers['User-Agent'] = 'Fusio v' . Base::getVersion();

        return $result;
    }

    protected function executeRequest(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        // parse body
        $parser = $this->templateFactory->newTextParser();
        $body   = $parser->parse($request, $context, $configuration->get('body'));

        // build request
        $method   = $configuration->get('method') ?: 'POST';
        $headers  = $this->parserHeaders($configuration->get('headers'));
        $request  = new Request(new Url($configuration->get('url')), $method, $headers, $body);

        return $this->http->request($request);
    }
}
