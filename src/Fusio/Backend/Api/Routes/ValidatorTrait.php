<?php

namespace Fusio\Backend\Api\Routes;

use Fusio\Backend\Filter\Routes as Filter;
use PSX\Filter as PSXFilter;
use PSX\Validate;
use PSX\Validate\Property;
use PSX\Validate\RecordValidator;

trait ValidatorTrait
{
	protected function getValidator()
	{
		return new RecordValidator(new Validate(), array(
			new Property('id', Validate::TYPE_INTEGER, array(new PSXFilter\PrimaryKey($this->tableManager->getTable('Fusio\Backend\Table\Routes')))),
			new Property('methods', Validate::TYPE_STRING, array(new Filter\Methods())),
			new Property('path', Validate::TYPE_STRING, array(new Filter\Path())),
			new Property('controller', Validate::TYPE_STRING, array(new Filter\Controller())),
			new Property('config', Validate::TYPE_ARRAY),
		));
	}
}
