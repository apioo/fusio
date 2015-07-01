<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Action;

use Doctrine\DBAL\Connection;
use Fusio\ActionInterface;
use Fusio\ConfigurationException;
use Fusio\Context;
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use Fusio\Request;
use Fusio\Response;
use PSX\Data\Writer;

/**
 * HttpRequest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class HttpRequest implements ActionInterface
{
    /**
     * @Inject
     * @var PSX\Http
     */
    protected $http;

    public function getName()
    {
        return 'HTTP-Request';
    }

    public function handle(Request $request, Parameters $configuration, Context $context)
    {
        $headers  = array('User-Agent' => 'Fusio');
        $writer   = new Writer\Json();
        $body     = $writer->write($request->getBody());
        $request  = new PostRequest($configuration->get('url'), $headers, $body);

        $response = $this->http->request($request);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return new Response(200, [], [
                'success' => true,
                'message' => 'Request successful'
            ]);
        } else {
            return new Response(200, [], [
                'success' => false,
                'message' => 'Request failed'
            ]);
        }
    }

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Input('url', 'Url', 'text', 'Sends an HTTP POST request to the given url. The body contains the json encoded data from the request'));

        return $form;
    }
}
