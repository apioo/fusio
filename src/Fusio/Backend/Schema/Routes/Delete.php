<?php

namespace Fusio\Backend\Schema\Routes;

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
		$schema = $this->getSchema('Fusio\Backend\Schema\Routes');
		$schema->getChild('id')->setRequired(true);

		return $schema;
	}
}
