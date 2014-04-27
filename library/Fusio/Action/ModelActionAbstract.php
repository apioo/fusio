<?php

namespace Fusio\Action;

use Fusio\Parameter;
use PSX\Http\Request;

abstract class ModelActionAbstract extends ConnectionActionAbstract
{
	protected function import(Request $request, $modelId)
	{
		$reader   = $this->getRequestReader($request);
		$importer = $reader->getDefaultImporter();

		if($importer instanceof ImporterInterface)
		{
			return $importer->import($modelId, $reader->read($request));
		}
		else
		{
			throw new RuntimeException('Reader has no default importer');
		}
	}

	protected function getRequestReader(Request $request)
	{
		$reader = $this->container->get('readerFactory')->getReaderByContentType($request->getHeader('Content-Type'));

		if($reader === null)
		{
			$reader = $this->container->get('readerFactory')->getDefaultReader();
		}

		if($reader === null)
		{
			throw new NotFoundException('Could not find fitting data reader');
		}
	}
}
