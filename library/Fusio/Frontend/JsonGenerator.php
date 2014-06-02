<?php

namespace Fusio\Frontend;

use Fusio\Entity\Model;

class JsonGenerator
{
	public function generate(Model $model, $indent = 2)
	{
		$result = '{' . "\n";
		$fields = $model->getFields();

		foreach($fields as $i => $field)
		{
			if($field->getType() == 'array')
			{
				if($field->getReference())
				{
					$value = '[' . $this->generate($field->getReference(), $indent + 2) . ', {' . "\n";
					$value.= str_repeat(' ', $indent * 2) . '...' . "\n" . str_repeat(' ', $indent) . '}]';
				}
				else
				{
					$value = 'null';
				}
			}
			else if($field->getType() == 'object')
			{
				if($field->getReference())
				{
					$value = $this->generate($field->getReference(), $indent + 2);
				}
				else
				{
					$value = 'null';
				}
			}
			else
			{
				$value = $field->getType();
			}

			$name = $field->getRequired() ? '<b>' . $field->getName() . '</b>' : $field->getName();

			$result.= str_repeat(' ', $indent) . '"' . $name . '": ' . $value;

			if($i < count($fields) - 1)
			{
				$result.= ',';
			}

			$result.= "\n";
		}

		$result.= str_repeat(' ', $indent - 2) . '}';

		return $result;
	}
}
