<?php

namespace App\Todo;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception as StatusCode;

class Row extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');

        $sql = 'SELECT id, 
                       status, 
                       title, 
                       insert_date AS insertDate 
                  FROM app_todo 
                 WHERE id = :id';

        $todo = $connection->fetchAssoc($sql, [
            'id' => $request->getUriFragment('todo_id')
        ]);

        if (empty($todo)) {
            throw new StatusCode\NotFoundException('Entry not available');
        }

        return $this->response->build(200, [], $todo);
    }
}
