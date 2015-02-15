<?php

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;

/**
 * App
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class App extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('app');
		$sb->integer('id');
		$sb->integer('status');
		$sb->string('name');
		$sb->string('url');
		$sb->string('appKey');
		$sb->string('appSecret');

		return $sb->getProperty();
	}
}
