<?php

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;

/**
 * Routes
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Routes extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('routes');
		$sb->integer('id');
		$sb->string('methods')
			->setMinLength(3)
			->setMaxLength(20);
		$sb->string('path');
		$sb->string('controller');

		return $sb->getProperty();
	}
}
