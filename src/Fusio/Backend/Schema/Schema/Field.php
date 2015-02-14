<?php

namespace Fusio\Backend\Schema\Schema;

use PSX\Data\SchemaAbstract;

/**
 * Field
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Field extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('field');
		$sb->integer('id');
		$sb->string('name');
		$sb->string('type');
		$sb->integer('refId');

		return $sb->getProperty();
	}
}
