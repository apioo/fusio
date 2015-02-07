<?php

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;

/**
 * Action
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Action extends SchemaAbstract
{
	public function getDefinition()
	{
		$config = $this->getSchemaBuilder('config');
		$config->string('url');
		$config->integer('connection');
		$config->string('sql');
		$config->string('collection');
		$config->string('criteria');
		$config->string('projection');

		$sb = $this->getSchemaBuilder('controller');
		$sb->integer('id');
		$sb->string('name');
		$sb->string('class');
		$sb->complexType($config->getProperty());

		return $sb->getProperty();
	}
}
