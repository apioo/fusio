<?php

namespace Fusio\Backend\Api\User;

use Fusio\Backend\Filter\User as Filter;
use PSX\Filter as PSXFilter;
use PSX\Validate;
use PSX\Validate\Property;
use PSX\Validate\RecordValidator;

trait ValidatorTrait
{
	protected function getValidator()
	{
		return new RecordValidator(new Validate(), array(
			new Property('id', Validate::TYPE_INTEGER, array(new PSXFilter\PrimaryKey($this->tableManager->getTable('Fusio\Backend\Table\User')))),
			new Property('status', Validate::TYPE_INTEGER),
			new Property('name', Validate::TYPE_STRING),
			new Property('scopes', Validate::TYPE_ARRAY),
			new Property('date', Validate::TYPE_OBJECT),
		));
	}
}
