<?php

namespace Fusio\Backend\Schema;

use PSX\Data\SchemaAbstract;

/**
 * Message
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Message extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('message');
		$sb->boolean('success');
		$sb->string('message');

		return $sb->getProperty();
	}
}
