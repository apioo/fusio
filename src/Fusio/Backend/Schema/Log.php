<?php

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;

/**
 * Log
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Log extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('log');
		$sb->integer('id');
		$sb->string('ip');
		$sb->string('method');
		$sb->string('path');
		$sb->dateTime('date');

		return $sb->getProperty();
	}
}
