<?php

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;

/**
 * Connection
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Connection extends SchemaAbstract
{
	public function getDefinition()
	{
		$config = $this->getSchemaBuilder('config');
		$config->string('url');
		$config->string('type');
		$config->string('host');
		$config->string('username');
		$config->string('password');
		$config->string('database');

		$sb = $this->getSchemaBuilder('connection');
		$sb->integer('id');
		$sb->string('name');
		$sb->string('class');
		$sb->complexType($config->getProperty());

		return $sb->getProperty();
	}
}
