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

use Fusio\Impl\ActionTestCaseTrait;
use Fusio\Impl\App;
use Fusio\Impl\Form\Builder;
use Fusio\Impl\Action\Soap\ClientFactoryInterface;
use PSX\Data\Record;
use PSX\Http\Response;
use PSX\Test\Environment;

/**
 * SoapRequestTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SoapRequestTest extends \PHPUnit_Framework_TestCase
{
    use ActionTestCaseTrait;

    public function testHandle()
    {
        $soapClient = $this->getMockBuilder('SoapClient')
            ->disableOriginalConstructor()
            ->setMethods(['__soapCall'])
            ->getMock();

        $soapClient->expects($this->once())
            ->method('__soapCall')
            ->with($this->equalTo('doFoo'), $this->equalTo(['foo', 'bar']))
            ->willReturn(['bar' => 'foo']);

        $soapClientFactory = $this->getMockBuilder('Fusio\Impl\Action\Soap\ClientFactoryInterface')
            ->setMethods(['factory'])
            ->getMock();

        $soapClientFactory->expects($this->once())
            ->method('factory')
            ->with($this->equalTo(null), $this->equalTo([
                'soap_version' => 2,
                'location'     => 'http://127.0.0.1/tests/soap.php',
                'uri'          => 'http://phpsx.org/ns/',
                'exceptions'   => true,
            ]))
            ->willReturn($soapClient);

        $action = new SoapRequest();
        $action->setTemplateFactory(Environment::getService('template_factory'));
        $action->setResponse(Environment::getService('response'));
        $action->setSoapClientFactory($soapClientFactory);

        $parameters = $this->getParameters([
            'version'   => SOAP_1_2,
            'url'       => 'http://127.0.0.1/tests/soap.php',
            'method'    => 'doFoo',
            'arguments' => json_encode(['foo', 'bar']),
        ]);

        $body = Record::fromArray([
            'foo' => 'bar'
        ]);

        $response = $action->handle($this->getRequest('POST', [], [], [], $body), $parameters, $this->getContext());

        $body = [
            'success' => true,
            'message' => 'Request successful'
        ];

        $this->assertInstanceOf('Fusio\Engine\ResponseInterface', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());
    }

    public function testGetForm()
    {
        $action  = new SoapRequest();
        $builder = new Builder();
        $factory = Environment::getService('form_element_factory');

        $action->configure($builder, $factory);

        $this->assertInstanceOf('Fusio\Impl\Form\Container', $builder->getForm());
    }
}
