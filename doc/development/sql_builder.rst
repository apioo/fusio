
SQL Builder
===========

Fusio contains a SQL builder which helps to create nested results based on
flat SQL results. The following example shows a complex action from an internal
project which uses the SQL builder to create a nested JSON response.

Action
------

.. code-block:: php
    
    <?php
    
    namespace App\Storagesystem;
    
    use Fusio\Engine\ActionAbstract;
    use Fusio\Engine\ContextInterface;
    use Fusio\Engine\ParametersInterface;
    use Fusio\Engine\RequestInterface;
    use PSX\Sql\Builder;
    use PSX\Sql\Condition;
    
    class Collection extends ActionAbstract
    {
        public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
        {
            /** @var \Doctrine\DBAL\Connection $connection */
            $connection = $this->connector->getConnection('System');
    
            $startIndex = (int) $request->getParameter('startIndex');
            $startIndex = $startIndex <= 0 ? 0 : $startIndex;
            $condition  = $this->getCondition($request);
            $builder    = new Builder($connection);
    
            $sql = 'SELECT article.id,
                           article.status,
                           article.articleNumber,
                           article.articleStock,
                           article.description,
                           article.comment,
                           article.removeDate,
                           article.insertDate,
                           location.id AS locationId,
                           location.name AS locationName,
                           agroup.id AS groupId,
                           agroup.name AS groupName,
                           supplier.id AS supplierId,
                           supplier.name AS supplierName
                      FROM app_article article 
                INNER JOIN app_location location
                        ON article.locationId = location.id
                INNER JOIN app_group agroup
                        ON article.groupId = agroup.id
                INNER JOIN app_supplier supplier
                        ON article.supplierId = supplier.id
                     WHERE article.status = 1 
                       AND ' . $condition->getExpression($connection->getDatabasePlatform()) . '
                  ORDER BY article.insertDate DESC 
                     LIMIT :startIndex, 32';
    
            $parameters = array_merge($condition->getValues(), ['startIndex' => $startIndex]);
            $definition = [
                'totalResults' => $builder->doValue('SELECT COUNT(*) AS cnt FROM app_article WHERE status = 1', [], $builder->fieldInteger('cnt')),
                'startIndex' => $startIndex,
                'entries' => $builder->doCollection($sql, $parameters, [
                    'id' => $builder->fieldInteger('id'),
                    'location' => [
                        'id' => $builder->fieldInteger('locationId'),
                        'name' => 'locationName',
                    ],
                    'group' => [
                        'id' => $builder->fieldInteger('groupId'),
                        'name' => 'groupName',
                    ],
                    'supplier' => [
                        'id' => $builder->fieldInteger('supplierId'),
                        'name' => 'supplierName',
                    ],
                    'articleNumber' => 'articleNumber',
                    'articleStock' => $builder->fieldInteger('articleStock'),
                    'description' => 'description',
                    'comment' => 'comment',
                    'insertDate' => $builder->fieldDateTime('insertDate'),
                    'links' => [
                        'self' => $builder->fieldReplace('/article/{id}'),
                    ]
                ])
            ];
    
            return $this->response->build(200, [], $builder->build($definition));
        }
    
        private function getCondition(RequestInterface $request)
        {
            $fields = [
                'location.name' => 'location',
                'agroup.name' => 'group',
                'supplier.name' => 'supplier',
                'article.articleNumber' => 'articleNumber',
                'article.description' => 'description',
                'article.serialNumber' => 'serialNumber',
                'article.comment' => 'comment',
            ];
    
            $condition = new Condition();
            foreach ($fields as $columnName => $parameterName) {
                $parameter = $request->getParameter($parameterName);
                if (!empty($parameter)) {
                    $condition->like($columnName, '%' . $parameter . '%');
                }
            }
    
            return $condition;
        }
    }
