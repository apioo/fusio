<?php

namespace Fusio\Backend\Schema\User;

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
		$schema = $this->getSchema('Fusio\Backend\Schema\User');
		$schema->get('id')->setRequired(true);

		return $schema;
	}
}
