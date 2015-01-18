<?php

namespace Fusio\Backend\Schema\Routes;

use PSX\Data\SchemaAbstract;

/**
 * Create
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Create extends SchemaAbstract
{
	public function getDefinition()
	{
		$schema = $this->getSchema('Fusio\Backend\Schema\Routes');
		$schema->getChild('methods')->setRequired(true);
		$schema->getChild('path')->setRequired(true);
		$schema->getChild('controller')->setRequired(true);

		return $schema;
	}
}
