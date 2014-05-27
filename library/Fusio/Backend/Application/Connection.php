<?php

namespace Fusio\Backend\Application;

use Fusio\Connection\Factory;
use PSX\Controller\HandlerApiAbstract;
use PSX\Data\RecordInterface;
use InvalidArgumentException;
use RuntimeException;

class Connection extends HandlerApiAbstract
{
	protected function getDefaultHandler()
	{
		return $this->getDoctrineManager()
			->getHandler(function($manager){
				return $manager->createQueryBuilder()
					->from('Fusio\Entity\Connection', 'connection');
			});
	}

	protected function beforeCreate(RecordInterface $record)
	{
		$this->checkConnection($record);
	}

	protected function beforeUpdate(RecordInterface $record)
	{
		$this->checkConnection($record);
	}

	protected function checkConnection(RecordInterface $record)
	{
		$params = json_decode($record->getParam(), true);

		if (!is_array($params)) {
			throw new InvalidArgumentException('Param must be an json encoded array');
		}

		try {
			Factory::factory($record->getType())->getConnector($params);
		} catch (\Exception $e) {
			throw new RuntimeException('Could not connect to datasource');
		}
	}
}
