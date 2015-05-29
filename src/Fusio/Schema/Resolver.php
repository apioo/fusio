<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Fusio\Schema;

use Doctrine\DBAL\Connection;
use PSX\Data\Schema\Parser\JsonSchema\Document;
use PSX\Data\Schema\Parser\JsonSchema\RefResolver;
use PSX\Data\Schema\Parser\JsonSchema\ResolverInterface;
use PSX\Json;
use PSX\Uri;
use RuntimeException;

/**
 * Resolver
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Resolver implements ResolverInterface
{
	protected $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	public function resolve(Uri $uri, Document $source, RefResolver $resolver)
	{
		$name = ltrim($uri->getPath(), '/');
		$row  = $this->connection->fetchAssoc('SELECT name, source FROM fusio_schema WHERE name LIKE :name', array('name' => $name));

		if(!empty($row))
		{
			$data = Json::decode($row['source']);

			if(is_array($data))
			{
				$document = new Document($data, $resolver, null, $uri);

				return $document;
			}
			else
			{
				throw new RuntimeException(sprintf('Schema %s must be an object', $row['name']));
			}
		}
		else
		{
			throw new RuntimeException('Invalid schema reference ' . $name);
		}
	}
}
