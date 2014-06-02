<?php

namespace Fusio\Frontend\Application;

use Fusio\Controller\FrontendController;
use Fusio\Entity\Api;
use PSX\Sql;
use PSX\Exception;
use Fusio\Frontend\JsonGenerator;
use Fusio\Frontend\HtmlGenerator;

class Documentation extends FrontendController
{
	public function doIndex()
	{
		$this->getTemplate()->assign('apis', $this->getApis());
	}

	public function doDetail()
	{
		$api = $this->getEntityManager()
			->getRepository('Fusio\Entity\Api')
			->find($this->getUriFragments('id'));

		$generator = new JsonGenerator();
		$json = $generator->generate($api->getModel());

		$generator = new HtmlGenerator();
		$html = $generator->generate($api->getModel());

		$this->getTemplate()->assign('apis', $this->getApis());
		$this->getTemplate()->assign('api', $api);
		$this->getTemplate()->assign('html', $html);
		$this->getTemplate()->assign('json', $json);
		$this->getTemplate()->set('documentation/detail.tpl');
	}

	protected function getApis()
	{
		return $this->getEntityManager()
			->createQueryBuilder()
			->select('api')
			->from('Fusio\Entity\Api', 'api')
			->where('api.status = :status_live')
			->orWhere('api.status = :status_deprecated')
			->setParameter('status_live', Api::STATUS_LIVE)
			->setParameter('status_deprecated', Api::STATUS_DEPRECATED)
			->getQuery()
			->getResult();
	}
}
