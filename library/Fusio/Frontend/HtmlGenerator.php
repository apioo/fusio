<?php

namespace Fusio\Frontend;

use Fusio\Entity\Model;

class HtmlGenerator
{
	protected $references = array();

	public function generate(Model $model)
	{
		$result = '<h3>' . $model->getName() . '</h3>';
		$result.= '<table class="table" id="model-' . $model->getId() . '">' . "\n";
		$result.= '<colgroup>' . "\n";
		$result.= '  <col width="180" />' . "\n";
		$result.= '  <col width="180" />' . "\n";
		$result.= '  <col width="*" />' . "\n";
		$result.= '</colgroup>' . "\n";
		$result.= '<thead>' . "\n";
		$result.= '<tr>' . "\n";
		$result.= '  <th>Name</th>' . "\n";
		$result.= '  <th>Type</th>' . "\n";
		$result.= '  <th>Description</th>' . "\n";
		$result.= '</tr>' . "\n";
		$result.= '</thead>' . "\n";
		$result.= '<tbody>' . "\n";

		$fields = $model->getFields();
		$references = array();

		foreach($fields as $i => $field)
		{
			if($field->getType() == 'array')
			{
				if($field->getReference())
				{
					$type = '<a href="#model-' . $field->getReference()->getId() . '">' . $field->getReference()->getName() . '[]</a>';

					if(!in_array($field->getReference()->getId(), $this->references))
					{
						$references[] = $field->getReference();

						$this->references[] = $field->getReference()->getId();
					}
				}
			}
			else if($field->getType() == 'object')
			{
				if($field->getReference())
				{
					$type = '<a href="#model-' . $field->getReference()->getId() . '">' . $field->getReference()->getName() . '</a>';

					if(!in_array($field->getReference()->getId(), $this->references))
					{
						$references[] = $field->getReference();

						$this->references[] = $field->getReference()->getId();
					}
				}
			}
			else
			{
				$type = '<i>' . $field->getType() . '</i>';
			}

			$result.= '<tr>' . "\n";
			$result.= '  <td><code>' . $field->getName() . '</code>' . ($field->getRequired() ? '*' : '') . '</td>' . "\n";
			$result.= '  <td>' . (!empty($type) ? $type : '-') . '</td>' . "\n";
			$result.= '  <td>' . $field->getDescription() . '</td>' . "\n";
			$result.= '</tr>' . "\n";
		}

		$result.= '</tbody>' . "\n";
		$result.= '</table>' . "\n";

		foreach($references as $reference)
		{
			$result.= $this->generate($reference);
		}

		return $result;
	}
}

