<?php

namespace Fusio\Data;

use PSX\Data\Record\Definition\CollectionInterface;

class Collection implements CollectionInterface
{
	protected $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function getAll()
	{
	}

	public function get($name)
	{
		$model = $this->entityManager->createQueryBuilder()
			->select(array('model', 'fields'))
			->from('Fusio\Entity\Model', 'model')
			->innerJoin('model.fields', 'fields')
			->where('model.id = :id')
			->setParameter('id', $name)
			->getQuery()
			->getSingleResult();

		return $this->getDefinitionFromEntity($model);
	}

	public function add(DefinitionInterface $definition)
	{

	}

	public function has($name)
	{

	}

	public function merge(CollectionInterface $collection)
	{

	}

	protected function getDefinitionFromEntity($model)
	{
		$definition = new Definition();
		$fields     = $model->getFields();

		foreach($fields as $field)
		{
			if($field->getType() == 'array' && $field->getReference())
			{
				$child = new Property(null, 'object', $field->getReference()->getId());
			}
			else
			{
				$child = null;
			}

			$reference = $field->getReference() ? $field->getReference()->getId() : null;

			$property = new Property(
				$field->getName(), 
				$field->getType(), 
				$reference, 
				null, 
				$field->getRequired()
				null,
				null,
				$child);

			$definition->addProperty($property);
		}

		return $definition;
	}
}
