<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio;

use PSX\Data\Accessor;
use PSX\Data\RecordInterface;
use PSX\Validate;

/**
 * Body
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Body
{
	protected $data;
	protected $accessor;

	/**
	 * Data is either an RecordInterface if an schema was provided or an 
	 * array/DOMDocument if the passthru schema was selected
	 *
	 * @param mixed $data
	 */
	public function __construct($data)
	{
		$this->setData($data);
	}

	public function getData()
	{
		return $this->data;
	}

	public function setData($data)
	{
		$fields = $data instanceof RecordInterface || is_array($data) ? $this->normalize($data) : array();

		$this->data     = $data;
		$this->accessor = new Accessor(new Validate(), $fields);
	}

	public function get($key)
	{
		return $this->accessor->get($key);
	}

	public function toArray()
	{
		return array();
	}

	public static function fromArray(array $data)
	{
		return new self($data);
	}

	protected function normalize($data)
	{
		if($data instanceof RecordInterface)
		{
			$result = new \stdClass();
			$fields = $data->getRecordInfo()->getData();

			foreach($fields as $key => $value)
			{
				$result->$key = $this->normalize($value);
			}

			return $result;
		}
		else if(is_array($data))
		{
			$result = array();

			foreach($data as $key => $value)
			{
				$result[$key] = $this->normalize($value);
			}

			return $result;
		}
		else
		{
			return $data;
		}
	}
}
