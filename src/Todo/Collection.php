<?php

namespace App\Todo;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Framework\Test\Environment;

class Collection extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');

        $count   = $connection->fetchColumn('SELECT COUNT(*) FROM app_todo');
        $entries = $connection->fetchAll('SELECT * FROM app_todo WHERE status = 1 ORDER BY insertDate DESC LIMIT 16');

        return $this->response->build(200, [], [
            'totalResults' => $count,
            'entry' => $entries,
        ]);
    }
}
