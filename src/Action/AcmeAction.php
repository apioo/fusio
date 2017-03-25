<?php

namespace Fusio\Custom\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class AcmeAction extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('acme-mysql');

        $totalResults = $connection->fetchColumn('SELECT COUNT(*) FROM acme_news');
        $entries      = $connection->fetchAll('SELECT id, title, content, insertDate FROM acme_news ORDER BY insertDate DESC');

        return $this->response->build(200, [], [
            'totalResults' => $totalResults,
            'entry' => $entries,
        ]);
    }
}
