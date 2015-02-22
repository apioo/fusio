<?php

namespace Fusio\Backend\Schema\Schema;

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
		$schema = $this->getSchema('Fusio\Backend\Schema\Schema');
		$schema->get('name')->setRequired(true);

		return $schema;
	}
}
