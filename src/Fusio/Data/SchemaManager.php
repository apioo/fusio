<?php

namespace Fusio\Data;

use Doctrine\DBAL\Connection;
use PSX\Data\Schema\Builder;
use PSX\Data\Schema\SchemaManagerInterface;
use PSX\Data\Schema\InvalidSchemaException;

class SchemaManager implements SchemaManagerInterface
{
	protected $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	public function getSchema($schemaId)
	{
		$sql = '    SELECT fields.name, 
				           fields.type, 
				           fields.refId, 
				           schema.propertyName
				      FROM fusio_schema_fields fields
				INNER JOIN fusio_schema `schema`
				        ON schema.id = fields.schemaId
				     WHERE schema.id = :id';

		$fields = $this->connection->fetchAll($sql, array('id' => $schemaId));
		$name   = isset($fields[0]['propertyName']) ? $fields[0]['propertyName'] : null;

		if(!empty($name))
		{
			$schema = new Builder($name);

			foreach($fields as $field)
			{
				switch(strtolower($field['type']))
				{
					case 'array':
						$schema->arrayType($field['name'])
							->setPrototype($this->getSchema($field['refId'])->getDefinition());
						break;

					case 'boolean':
						$schema->boolean($field['name']);
						break;

					case 'date':
						$schema->date($field['name']);
						break;

					case 'datetime':
						$schema->dateTime($field['name']);
						break;

					case 'float':
						$schema->float($field['name']);
						break;

					case 'integer':
						$schema->integer($field['name']);
						break;

					case 'object':
						$schema->objectType($field['name'], $this->getSchema($field['refId'])->getDefinition());
						break;

					case 'string':
						$schema->string($field['name']);
						break;

					case 'time':
						$schema->time($field['name']);
						break;
				}
			}

			return new Schema($schema->getProperty());
		}
		else
		{
			throw new InvalidSchemaException('Could not find schema');
		}
	}
}
