<?php

namespace Fusio\Backend\Schema\Action;

use PSX\Data\SchemaAbstract;

/**
 * Update
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Update extends SchemaAbstract
{
	public function getDefinition()
	{
		$schema = $this->getSchema('Fusio\Backend\Schema\Action');
		$schema->getChild('id')->setRequired(true);

		return $schema;
	}
}
