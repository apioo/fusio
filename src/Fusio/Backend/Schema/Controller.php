<?php

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;

/**
 * Controller
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Controller extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('controller');
		$sb->integer('id');
		$sb->string('class');

		return $sb->getProperty();
	}
}
