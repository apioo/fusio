
# Connection

All classes inside this folder which implement the `Fusio\Engine\ConnectionInterface`
are listed in the connection dropdown. In the following a simple example of a 
custom connection.

    <?php
    
    namespace Fusio\Custom\Connection;
    
    use Fusio\Engine\ConnectionInterface;
    use Fusio\Engine\Form\BuilderInterface;
    use Fusio\Engine\Form\ElementFactoryInterface;
    use Fusio\Engine\ParametersInterface;
    
    class AcmeConnection implements ConnectionInterface
    {
        public function getName()
        {
            return 'Acme-Connection';
        }
    
        public function getConnection(ParametersInterface $config)
        {
            // @TODO returns a connection object
    
            return new \PDO($config->get('dsn'), $config->get('username'), $config->get('password'));
        }
    
        public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
        {
            $builder->add($elementFactory->newInput('dsn', 'DSN', 'text', 'The Data Source Name, or DSN, contains the information required to connect to the database.'));
            $builder->add($elementFactory->newInput('username', 'Username', 'text', 'The name of the database user'));
            $builder->add($elementFactory->newInput('password', 'Password', 'password', 'The password of the database user'));
        }
    }
