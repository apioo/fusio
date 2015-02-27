<?php

namespace Fusio\Backend\Schema\Scope;

use PSX\Data\SchemaAbstract;

/**
 * Delete
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Delete extends SchemaAbstract
{
	public function getDefinition()
	{
		$schema = $this->getSchema('Fusio\Backend\Schema\Scope');
		$schema->get('id')->setRequired(true);

		return $schema;
	}
}
