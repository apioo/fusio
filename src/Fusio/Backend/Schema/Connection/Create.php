<?php

namespace Fusio\Backend\Schema\Connection;

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
		$schema = $this->getSchema('Fusio\Backend\Schema\Connection');
		$schema->getChild('name')->setRequired(true);
		$schema->getChild('config')->setRequired(true);

		return $schema;
	}
}
