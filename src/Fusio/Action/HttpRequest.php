<?php

namespace Fusio\Action;

use Doctrine\DBAL\Connection;
use Fusio\ActionInterface;
use Fusio\ConfigurationException;
use Fusio\Parameters;
use Fusio\Body;
use Fusio\Form;
use Fusio\Form\Element;
use PSX\Util\CurveArray;

class HttpRequest implements ActionInterface
{
	/**
	 * @Inject
	 * @var PSX\Http
	 */
	protected $http;

	public function getName()
	{
		return 'HTTP-Request';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$headers  = array();
		$body     = json_encode($data->toArray());
		$request  = new PostRequest($configuration->get('url'), $headers, $body);

		$response = $this->http->request($request);

		if($response->getStatusCode() >= 200 && $response->getStatusCode() < 300)
		{
			return array(
				'success' => true,
				'message' => 'Request successful'
			);
		}
		else
		{
			return array(
				'success' => false,
				'message' => 'Request failed'
			);
		}
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Input('url', 'Url'));

		return $form;
	}
}
