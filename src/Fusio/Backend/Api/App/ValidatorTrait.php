<?php

namespace Fusio\Backend\Api\App;

use Fusio\Backend\Filter\App as Filter;
use PSX\Filter as PSXFilter;
use PSX\Validate;
use PSX\Validate\Property;
use PSX\Validate\RecordValidator;

trait ValidatorTrait
{
	protected function getValidator()
	{
		return new RecordValidator(new Validate(), array(
			new Property('id', Validate::TYPE_INTEGER, array(new PSXFilter\PrimaryKey($this->tableManager->getTable('Fusio\Backend\Table\App')))),
			new Property('status', Validate::TYPE_INTEGER),
			new Property('name', Validate::TYPE_STRING),
			new Property('url', Validate::TYPE_STRING),
			new Property('appKey', Validate::TYPE_STRING),
			new Property('appSecret', Validate::TYPE_STRING),
		));
	}
}
