<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2013 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Dependency;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Fusio\ApiManager;
use PSX\Dependency\DefaultContainer;
use PSX\Handler\Doctrine\Manager as DoctrineManager;
use PSX\Handler\Doctrine\RecordHydrator;

/**
 * Container
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Container extends DefaultContainer
{
	public function getApiManager()
	{
		return new ApiManager();
	}

	public function getApp()
	{
		return new User($this->getParameter('app.id'));
	}

	public function getDoctrineManager()
	{
		return new DoctrineManager($this->get('entity_manager'));
	}

	public function getEntityManager()
	{
		$paths     = array('library/Fusio/Entity');
		$isDevMode = $this->get('config')->get('psx_debug');
		$dbParams  = array(
			'driver'   => 'pdo_mysql',
			'user'     => $this->get('config')->get('psx_sql_user'),
			'password' => $this->get('config')->get('psx_sql_pw'),
			'dbname'   => $this->get('config')->get('psx_sql_db'),
		);

		$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
		$config->addCustomHydrationMode(RecordHydrator::HYDRATE_RECORD, 'PSX\Handler\Doctrine\RecordHydrator');

		$entityManager = EntityManager::create($dbParams, $config);

		return $entityManager;
	}
}

