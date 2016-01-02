<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace Fusio\Impl\Template\Parser;

use Fusio\Impl\Context;
use Fusio\Impl\Request;
use Fusio\Impl\Template\Extension;
use Fusio\Impl\Template\StackLoader;
use PSX\Data\Accessor;
use PSX\DisplayException;
use PSX\Validate;

/**
 * BaseAbstract
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
abstract class BaseAbstract
{
    protected $loader;
    protected $twig;
    protected $cacheKey;

    public function __construct($debug, $cache = true, $cacheKey = null)
    {
        $this->loader = new StackLoader();
        $this->twig   = new \Twig_Environment($this->loader, [
            'debug'            => $debug,
            'cache'            => $cache ? PSX_PATH_CACHE : false,
            'autoescape'       => false,
            'auto_reload'      => true,
            'strict_variables' => false,
        ]);

        $extensions = $this->twig->getExtensions();
        foreach ($extensions as $name => $extension) {
            $this->twig->removeExtension($name);
        }

        $this->twig->addExtension($this->getExtension());

        $this->cacheKey = $cacheKey;
    }

    public function parse(Request $request, Context $context, $template)
    {
        $cacheKey     = $context->getAction()->getId();
        $lastModified = $context->getAction()->getDate();

        if ($this->cacheKey !== null) {
            $cacheKey.= '_' . $this->cacheKey;
        }

        $this->loader->set($template, $cacheKey, $lastModified);

        try {
            return $this->twig->render($cacheKey, [
                'request' => $request,
                'context' => $context,
                'body'    => new Accessor(new Validate(), $request->getBody()),
            ]);
        } catch (\Twig_Error_Runtime $e) {
            // if we have an display exception throw the original exception
            if ($e->getPrevious() instanceof DisplayException) {
                throw $e->getPrevious();
            } else {
                throw $e;
            }
        }
    }

    /**
     * Returns the used twig extension
     *
     * @return \Twig_Extension
     */
    abstract protected function getExtension();
}
