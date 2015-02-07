<?php

namespace Fusio\Backend\Schema\Action;

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
		$schema = $this->getSchema('Fusio\Backend\Schema\Action');
		$schema->getChild('name')->setRequired(true);
		$schema->getChild('config')->setRequired(true);

		return $schema;
	}
}
