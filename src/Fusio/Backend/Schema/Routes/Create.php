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
		$schema->get('methods')->setRequired(true);
		$schema->get('path')->setRequired(true);
		$schema->get('controller')->setRequired(true);

		return $schema;
	}
}
