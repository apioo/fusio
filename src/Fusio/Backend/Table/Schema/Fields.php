<?php

namespace Fusio\Backend\Table\Schema;

use PSX\Sql\TableAbstract;

/**
 * Fields
 *
 * @see http://phpsx.org/doc/concept/table.html
 */
class Fields extends TableAbstract
{
	public function getName()
	{
		return 'fusio_schema_fields';
	}

	public function getColumns()
	{
		return array(
			'id' => self::TYPE_INT | self::AUTO_INCREMENT | self::PRIMARY_KEY,
			'schemaId' => self::TYPE_INT,
			'refId' => self::TYPE_INT,
			'name' => self::TYPE_VARCHAR,
			'type' => self::TYPE_VARCHAR,
			'required' => self::TYPE_INT,
			'min' => self::TYPE_INT,
			'max' => self::TYPE_INT,
			'pattern' => self::TYPE_VARCHAR,
			'enumeration' => self::TYPE_VARCHAR,
		);
	}

	public function deleteAllFromSchema($schemaId)
	{
		$sql = 'DELETE FROM fusio_schema_fields
				      WHERE schemaId = :id';

		$this->connection->executeQuery($sql, array('id' => $schemaId));
	}
}
