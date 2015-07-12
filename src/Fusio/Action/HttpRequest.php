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

namespace Fusio\Action;

use Fusio\ActionInterface;
use Fusio\Context;
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use Fusio\Request;
use Fusio\Response;
use Fusio\Template\Parser;
use PSX\Data\Writer;
use PSX\Http;
use PSX\Http\PostRequest;

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
     * @var \Fusio\Template\Parser
     */
    protected $templateParser;

    public function getName()
    {
        return 'HTTP-Request';
    }

    public function handle(Request $request, Parameters $configuration, Context $context)
    {
        // parse json
        $headers  = array('User-Agent' => 'Fusio');
        $body     = $this->templateParser->parse($request, $configuration, $context, $configuration->get('body'));

        $request  = new PostRequest($configuration->get('url'), $headers, $body);
        $response = $this->http->request($request);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return new Response(200, [], [
                'success' => true,
                'message' => 'Request successful'
            ]);
        } else {
            return new Response(500, [], [
                'success' => false,
                'message' => 'Request failed'
            ]);
        }
    }

    public function getForm()
    {
        $form = new Form\Container();
        $form->add(new Element\Input('url', 'Url', 'text', 'Sends an HTTP POST request to the given url'));
        $form->add(new Element\TextArea('body', 'Body', 'text', 'The body for the POST request'));

        return $form;
    }

    public function setHttp(Http $http)
    {
        $this->http = $http;
    }

    public function setTemplateParser(Parser $templateParser)
    {
        $this->templateParser = $templateParser;
    }
}
