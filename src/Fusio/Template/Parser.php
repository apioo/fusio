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

namespace Fusio\Template;

use Fusio\Context;
use Fusio\Parameters;
use Fusio\Request;
use Fusio\Template\Filter\Prepare;
use PSX\Data\Accessor;
use PSX\Validate;

/**
 * Parser
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Parser
{
    protected $loader;
    protected $twig;

    public function __construct($debug, $cache = true)
    {
        $this->loader = new StackLoader();
        $this->twig   = new Environment($this->loader, [
            'debug'            => $debug,
            'cache'            => $cache ? PSX_PATH_CACHE : false,
            'autoescape'       => false,
            'auto_reload'      => true,
            'strict_variables' => false,
        ]);
    }

    public function parse(Request $request, Parameters $configuration, Context $context, $data)
    {
        $cacheKey     = $configuration->get(Parameters::ACTION_ID);
        $lastModified = $configuration->get(Parameters::ACTION_LAST_MODIFIED);

        $this->loader->set($data, $cacheKey, $lastModified);

        $this->twig->getFilter(Prepare::FILTER_NAME)->getCallable()->clear();

        return $this->twig->render($configuration->get(Parameters::ACTION_ID), [
            'request' => $request,
            'context' => $context,
            'body'    => new Accessor(new Validate(), $request->getBody()),
        ]);
    }

    public function getSqlParameters()
    {
        return $this->twig->getFilter(Prepare::FILTER_NAME)->getCallable()->getParameters();
    }
}
