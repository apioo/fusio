<?php

namespace Fusio\Backend\Api\Connection;

use Fusio\Form;
use PSX\Controller\ApiAbstract;

/**
 * ListConnections
 */
class ListConnections extends ApiAbstract
{
	/**
	 * @Inject
	 * @var Fusio\ConnectionParser
	 */
	protected $connectionParser;

	public function doIndex()
	{
		$this->setBody(array(
			'connections' => $this->connectionParser->getClasses()
		));
	}

	public function doDetail()
	{
		$className = $this->getParameter('class');
		$form      = $this->connectionParser->getForm($className);

		if($form instanceof Form\Container)
		{
			$this->setBody($form);
		}
		else
		{
			throw new \RuntimeException('Invalid connection class');
		}
	}
}
