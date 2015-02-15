<?php

namespace Fusio\Backend\Schema\App;

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
		$schema = $this->getSchema('Fusio\Backend\Schema\App');
		$schema->getChild('status')->setRequired(true);
		$schema->getChild('name')->setRequired(true);
		$schema->getChild('url')->setRequired(true);

		return $schema;
	}
}
