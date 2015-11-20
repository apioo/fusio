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
use Fusio\Impl\Action\Soap\ClientFactoryInterface;
use Fusio\Impl\Base;
use PSX\Http;
use PSX\Http\Request;
use PSX\Json;
use SoapClient;
use Symfony\Component\Yaml\Parser;

/**
 * SoapRequest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SoapRequest implements ActionInterface
{
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

    /**
     * @var \Fusio\Action\Soap\ClientFactoryInterface
     */
    protected $soapClientFactory;

    public function __construct()
    {
        $this->soapClientFactory = new Soap\NativeClientFactory();
    }

    public function getName()
    {
        return 'SOAP-Request';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $response = $this->executeRequest($request, $configuration, $context);

        return $this->response->build(200, [], [
            'success' => true,
            'message' => 'Request successful'
        ]);
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newSelect('version', 'Version', [SOAP_1_1 => '1.1', SOAP_1_2 => '1.2'], 'SOAP version'));
        $builder->add($elementFactory->newInput('url', 'Url', 'text', 'SOAP endpoint'));
        $builder->add($elementFactory->newInput('wsdl', 'WSDL', 'text', 'Location of the WSDL specification'));
        $builder->add($elementFactory->newInput('method', 'Method', 'text', 'Name of the remote method'));
        $builder->add($elementFactory->newInput('username', 'Username', 'text', 'Optional username for authentication'));
        $builder->add($elementFactory->newInput('password', 'Password', 'text', 'Optional password for authentication'));
        $builder->add($elementFactory->newTextArea('arguments', 'Arguments', 'json', 'A JSON encoded array or object which is passed as argument to the remote method'));
    }

    public function setTemplateFactory(FactoryInterface $templateFactory)
    {
        $this->templateFactory = $templateFactory;
    }

    public function setResponse(ResponseFactoryInterface $response)
    {
        $this->response = $response;
    }

    public function setSoapClientFactory(ClientFactoryInterface $soapClientFactory)
    {
        $this->soapClientFactory = $soapClientFactory;
    }

    protected function executeRequest(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        // parse arguments
        $parser    = $this->templateFactory->newTextParser();
        $arguments = $parser->parse($request, $context, $configuration->get('arguments'));

        if (!empty($arguments)) {
            $arguments = (array) Json::decode($arguments);
        } else {
            $arguments = array();
        }

        // build request
        $wsdl     = $configuration->get('wsdl')     ?: null;
        $login    = $configuration->get('username') ?: null;
        $password = $configuration->get('password') ?: null;

        if (empty($wsdl)) {
            $wsdl    = null;
            $options = [
                'soap_version' => $configuration->get('version'),
                'location'     => $configuration->get('url'),
                'uri'          => 'http://phpsx.org/ns/',
            ];
        } else {
            $options = [
                'soap_version' => $configuration->get('version'),
            ];
        }

        $options['exceptions'] = true;

        if (!empty($login) && !empty($password)) {
            $options['login']    = $login;
            $options['password'] = $password;
        }

        $client = $this->soapClientFactory->factory($wsdl, $options);

        return $client->__soapCall($configuration->get('method'), $arguments);
    }
}
