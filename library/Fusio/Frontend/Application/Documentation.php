<?php

namespace Fusio\Frontend\Application;

use Fusio\Controller\FrontendController;
use PSX\Sql;
use PSX\Exception;

class Documentation extends FrontendController
{
	public function doIndex()
	{
		$sql = 'SELECT 
					* 
				FROM 
					`fusio_api` 
				ORDER BY 
					`path` ASC';

		$result = $this->getSql()->getAll($sql, array(), Sql::FETCH_OBJECT, '\Fusio\Api', array($this->getSql()));

		$this->getTemplate()->assign('apis', $result);
	}

	public function doDetail()
	{
		$sql = 'SELECT 
					*
				FROM 
					`fusio_api` 
				WHERE 
					`id` = ?';

		$id     = $this->getUriFragments('id');
		$result = $this->getSql()->getRow($sql, array($id), Sql::FETCH_OBJECT, '\Fusio\Api', array($this->getSql()));

		if (empty($result)) {
			throw new Exception('Invalid api');
		}

		$this->getTemplate()->assign('api', $result);
		$this->getTemplate()->set($this->getResourcePath() . '/documentation/detail.tpl');
	}
}
