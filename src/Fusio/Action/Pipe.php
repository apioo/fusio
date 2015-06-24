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
use Fusio\Form;
use Fusio\Form\Element;
use Fusio\Parameters;
use Fusio\Request;
use PSX\Data\Record;
use PSX\Data\Record\GraphTraverser;
use PSX\Data\Record\Visitor\StdClassSerializeVisitor;

/**
 * Pipe
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Pipe implements ActionInterface
{
	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 * @var Fusio\Processor
	 */
	protected $processor;

	/**
	 * @Inject
	 * @var PSX\Data\Record\ImporterManager
	 */
	protected $importerManager;

	public function getName()
	{
		return 'Pipe';
	}

	public function handle(Request $request, Parameters $configuration)
	{
		$response = $this->processor->execute($configuration->get('source'), $request);

		$visitor   = new StdClassSerializeVisitor();
		$traverser = new GraphTraverser();
		$traverser->traverse($response->getBody(), $visitor);

		$importer = $this->importerManager->getImporterByInstance('PSX\Data\Record\Importer\Record');
		$body     = $importer->importer(new Record(), $visitor->getObject());

		return $this->processor->execute($configuration->get('destination'), $request->withBody($body));
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Action('source', 'Source', $this->connection, 'Executes this action and uses the response as input for the destination action'));
		$form->add(new Element\Action('destination', 'Destination', $this->connection, 'The action which receives the response from the source action and returns the response'));

		return $form;
	}
}
