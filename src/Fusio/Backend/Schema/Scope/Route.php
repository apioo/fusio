<?php

namespace Fusio\Backend\Schema\Scope;

use PSX\Data\SchemaAbstract;

/**
 * Route
 *
 * @see http://phpsx.org/doc/concept/schema.html
 */
class Route extends SchemaAbstract
{
	public function getDefinition()
	{
		$sb = $this->getSchemaBuilder('route');
		$sb->integer('routeId');
		$sb->boolean('allow');
		$sb->string('methods');

		return $sb->getProperty();
	}
}
