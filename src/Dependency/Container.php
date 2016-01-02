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

namespace Fusio\Impl\Dependency;

use Fusio\Impl\App;
use Fusio\Impl\Base;
use Fusio\Impl\Connector;
use Fusio\Impl\Console;
use Fusio\Impl\Data\SchemaManager;
use Fusio\Impl\Factory;
use Fusio\Impl\Form;
use Fusio\Impl\Loader\DatabaseRoutes;
use Fusio\Impl\Loader\ResourceListing;
use Fusio\Impl\Loader\RoutingParser;
use Fusio\Impl\Logger;
use Fusio\Impl\Parser;
use Fusio\Impl\Processor;
use Fusio\Impl\Response;
use Fusio\Impl\Schema;
use Fusio\Impl\Template;
use Fusio\Impl\Validate;
use Monolog\Handler as LogHandler;
use PSX\Api;
use PSX\Console as PSXCommand;
use PSX\Console\Reader;
use PSX\Data\Importer;
use PSX\Dependency\DefaultContainer;
use PSX\Log;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command as SymfonyCommand;

/**
 * Container
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Container extends DefaultContainer
{
    use Authorization;
    use Service;

    /**
     * @return \Psr\Log\LoggerInterface
     */
    /*
    public function getLogger()
    {
        $logger = new SystemLogger('psx');
        $logger->pushHandler(new LogHandler\NullHandler());

        return $logger;
    }
    */

    /**
     * @return \PSX\Loader\RoutingParserInterface
     */
    public function getRoutingParser()
    {
        return new DatabaseRoutes($this->get('connection'));
    }

    /**
     * @return \PSX\Loader\LocationFinderInterface
     */
    public function getLoaderLocationFinder()
    {
        return new RoutingParser($this->get('connection'));
    }

    /**
     * @return \PSX\Data\Schema\SchemaManagerInterface
     */
    public function getApiSchemaManager()
    {
        return new SchemaManager($this->get('connection'));
    }

    /**
     * @return \PSX\Api\Resource\ListingInterface
     */
    public function getResourceListing()
    {
        $resourceListing = new ResourceListing($this->get('routing_parser'), $this->get('controller_factory'));

        if ($this->get('config')->get('psx_debug')) {
            return $resourceListing;
        } else {
            return new Api\Resource\Listing\CachedListing($resourceListing, $this->get('cache'));
        }
    }

    /**
     * @return \Fusio\Engine\LoggerInterface
     */
    public function getApiLogger()
    {
        return new Logger($this->get('connection'));
    }

    /**
     * @return \Fusio\Engine\Parser\ParserInterface
     */
    public function getActionParser()
    {
        return new Parser\Action(
            $this->get('action_factory'),
            $this->get('connection'),
            'fusio_action_class',
            'Fusio\Engine\ActionInterface'
        );
    }

    /**
     * @return \Fusio\Engine\Factory\ActionInterface
     */
    public function getActionFactory()
    {
        return new Factory\Action($this->get('object_builder'));
    }

    /**
     * @return \Fusio\Engine\ProcessorInterface
     */
    public function getProcessor()
    {
        return new Processor(
            new Processor\DatabaseRepository($this->get('connection')),
            $this->get('action_factory')
        );
    }

    /**
     * @return \Fusio\Engine\Parser\ParserInterface
     */
    public function getConnectionParser()
    {
        return new Parser\Connection(
            $this->get('connection_factory'),
            $this->get('connection'),
            'fusio_connection_class',
            'Fusio\Engine\ConnectionInterface'
        );
    }

    /**
     * @return \Fusio\Engine\Factory\ConnectionInterface
     */
    public function getConnectionFactory()
    {
        return new Factory\Connection($this->get('object_builder'));
    }

    /**
     * @return \Fusio\Engine\ConnectorInterface
     */
    public function getConnector()
    {
        return new Connector($this->get('connection'), $this->get('connection_factory'));
    }

    /**
     * @return \Fusio\Engine\Schema\ParserInterface
     */
    public function getSchemaParser()
    {
        return new Schema\Parser($this->get('connection'));
    }

    /**
     * @return \Fusio\Engine\Schema\LoaderInterface
     */
    public function getSchemaLoader()
    {
        return new Schema\Loader($this->get('connection'));
    }

    /**
     * @return \Fusio\Engine\App\LoaderInterface
     */
    public function getAppLoader()
    {
        return new App\Loader($this->get('connection'));
    }

    /**
     * @return \Fusio\Engine\Template\FactoryInterface
     */
    public function getTemplateFactory()
    {
        return new Template\Factory($this->get('config')->get('psx_debug'));
    }

    /**
     * @return \Fusio\Impl\Validate\ServiceContainer
     */
    public function getValidateServiceContainer()
    {
        $container = new Validate\ServiceContainer();
        $container->set('database', new Validate\Service\Database($this->get('connector')));
        $container->set('filter', new Validate\Service\Filter());

        return $container;
    }

    /**
     * @return \Fusio\Engine\Form\ElementFactoryInterface
     */
    public function getFormElementFactory()
    {
        return new Form\ElementFactory($this->get('connection'));
    }

    /**
     * @return \Fusio\Engine\Response\FactoryInterface
     */
    public function getResponse()
    {
        return new Response\Factory();
    }

    /**
     * @return \Symfony\Component\Console\Application
     */
    public function getConsole()
    {
        $application = new Application('fusio', Base::getVersion());

        $this->appendConsoleCommands($application);

        return $application;
    }

    protected function appendConsoleCommands(Application $application)
    {
        // psx commands
        $application->add(new PSXCommand\ContainerCommand($this));
        $application->add(new PSXCommand\RouteCommand($this->get('routing_parser')));
        $application->add(new PSXCommand\ServeCommand($this->get('config'), $this->get('dispatch'), $this->get('console_reader')));

        // fusio commands
        $application->add(new Console\InstallCommand($this->get('connection')));
        $application->add(new Console\AddUserCommand($this->get('user_service')));
        $application->add(new Console\RegisterAdapterCommand($this->get('dispatch'), $this->get('connection'), $this->get('logger')));

        $application->add(new Console\ListActionCommand($this->get('action_parser')));
        $application->add(new Console\DetailActionCommand($this->get('action_factory'), $this->get('connection')));

        $application->add(new Console\ListConnectionCommand($this->get('connection_parser')));
        $application->add(new Console\DetailConnectionCommand($this->get('connection_factory'), $this->get('connection')));

        $application->add(new Console\ExportSchemaCommand($this->get('connection')));

        // symfony commands
        $application->add(new SymfonyCommand\HelpCommand());
        $application->add(new SymfonyCommand\ListCommand());
    }
}
