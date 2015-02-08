<?php

namespace Fusio\Backend\Schema\Routes;

use PSX\Data\SchemaAbstract;

/**
 * Config
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Config extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('config');
		$sb->string('method');
		$sb->integer('request');
		$sb->integer('response');
		$sb->integer('action');

		return $sb->getProperty();
	}
}
