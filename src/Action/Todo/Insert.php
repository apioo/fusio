<?php

namespace App\Action\Todo;

use App\Model\Todo;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class Insert extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');

        $body = $request->getPayload();
        $now  = new \DateTime();

        assert($body instanceof Todo);

        $connection->insert('app_todo', [
            'status' => 1,
            'title' => $body->getTitle(),
            'insert_date' => $now->format('Y-m-d H:i:s'),
        ]);

        return $this->response->build(201, [], [
            'success' => true,
            'message' => 'Insert successful',
        ]);
    }
}
