<?php

namespace Fusio\Backend\Api\Action;

use Fusio\Form;
use PSX\Controller\ApiAbstract;

/**
 * ListActions
 */
class ListActions extends ApiAbstract
{
	/**
	 * @Inject
	 * @var Fusio\ActionParser
	 */
	protected $actionParser;

	public function doIndex()
	{
		$this->setBody(array(
			'actions' => $this->actionParser->getActions()
		));
	}

	public function doDetail()
	{
		$className = $this->getParameter('class');
		$form      = $this->actionParser->getForm($className);

		if($form instanceof Form\Container)
		{
			$this->setBody($form);
		}
		else
		{
			throw new \RuntimeException('Invalid action class');
		}
	}
}
