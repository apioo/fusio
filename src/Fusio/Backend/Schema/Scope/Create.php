<?php

namespace Fusio\Backend\Schema\Scope;

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
		$schema = $this->getSchema('Fusio\Backend\Schema\Scope');
		$schema->get('name')->setRequired(true);
		$schema->get('routes')->setRequired(true);

		return $schema;
	}
}
