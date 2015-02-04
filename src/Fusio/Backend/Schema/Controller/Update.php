<?php

namespace Fusio\Backend\Schema\Controller;

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
		$schema = $this->getSchema('Fusio\Backend\Schema\Controller');
		$schema->getChild('id')->setRequired(true);

		return $schema;
	}
}
