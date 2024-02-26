<?php
namespace App\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\ContextInterface;
use GuzzleHttp\Client;
use Doctrine\DBAL\DriverManager;

class Mmitest extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        $db = $this->connector->getConnection('System');
        $now = new \DateTime();
        $db->insert('danapay_callbacks', array(
            'app_id' => 24,
            'url' => 'test',
            'request' => 'test',
            'response' => 'test',
            'insert_date' => $now->format('Y-m-d H:i:s')
        ));
    }
}