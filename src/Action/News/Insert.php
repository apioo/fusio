<?php

namespace Fusio\Custom\Action\News;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Framework\Util\Uuid;

class Insert extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('Acme-Mysql');

        $body = $request->getBody();
        $now  = new \DateTime();

        $connection->insert('acme_news', [
            'id' => Uuid::pseudoRandom(),
            'title' => $body->title,
            'content' => $body->content,
            'insertDate' => $now->format('Y-m-d H:i:s'),
        ]);

        return $this->response->build(201, [], [
            'success' => true,
            'message' => 'News successful inserted',
        ]);
    }
}
