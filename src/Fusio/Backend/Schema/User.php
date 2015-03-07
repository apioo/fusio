<?php

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;
use PSX\Data\Schema\Property;

/**
 * User
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class User extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('user');
		$sb->integer('id');
		$sb->integer('status');
		$sb->string('name');
		$sb->arrayType('scopes')
			->setPrototype(new Property\String('name'));
		$sb->dateTime('date');

		return $sb->getProperty();
	}
}
