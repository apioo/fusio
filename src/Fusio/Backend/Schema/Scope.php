<?php

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;

/**
 * Scope
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Scope extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('scope');
		$sb->integer('id');
		$sb->string('name');
		$sb->arrayType('routes')
			->setPrototype($this->getSchema('Fusio\Backend\Schema\Scope\Route'));

		return $sb->getProperty();
	}
}
