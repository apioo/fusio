<?php

namespace Fusio\Backend\Api\Scope;

use Fusio\Backend\Filter\Scope as Filter;
use PSX\Filter as PSXFilter;
use PSX\Validate;
use PSX\Validate\Property;
use PSX\Validate\RecordValidator;

trait ValidatorTrait
{
	protected function getValidator()
	{
		return new RecordValidator(new Validate(), array(
			new Property('id', Validate::TYPE_INTEGER, array(new PSXFilter\PrimaryKey($this->tableManager->getTable('Fusio\Backend\Table\Scope')))),
			new Property('name', Validate::TYPE_STRING),
			new Property('routes', Validate::TYPE_ARRAY),
		));
	}
}
