<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class AnonymousPurgeCron extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $db = $this->connector->getConnection('System');

        $db->executeStatement("DELETE FROM danapay_app_operations WHERE payment_id IS NULL AND DATE_ADD(created_at, INTERVAL 48 HOUR) < NOW()");
    }
}