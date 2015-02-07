<?php

namespace Fusio\Form;

use PSX\Data\CollectionAbstract;
use PSX\Data\RecordInfo;

class Container extends CollectionAbstract
{
	public function getRecordInfo()
	{
		return new RecordInfo('container', array(
			'element' => $this->collection
		));
	}
}
