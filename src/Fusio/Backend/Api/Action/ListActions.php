<?php

namespace Fusio\Backend\Api\Action;

use Fusio\Backend\Api\Authorization\ProtectionTrait;
use Fusio\Form;
use PSX\Controller\ApiAbstract;

/**
 * ListActions
 */
class ListActions extends ApiAbstract
{
	use ProtectionTrait;

	/**
	 * @Inject
	 * @var Fusio\ActionParser
	 */
	protected $actionParser;

	public function doIndex()
	{
		$this->setBody(array(
			'actions' => $this->actionParser->getClasses()
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
