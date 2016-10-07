
# Action

All classes inside this folder which implement the `Fusio\Engine\ActionInterface`
are listed in the action dropdown. In the following a simple example of a 
custom action.

    <?php
    
    namespace Fusio\Custom\Action;
    
    use Fusio\Engine\ActionAbstract;
    use Fusio\Engine\ContextInterface;
    use Fusio\Engine\Form\BuilderInterface;
    use Fusio\Engine\Form\ElementFactoryInterface;
    use Fusio\Engine\ParametersInterface;
    use Fusio\Engine\RequestInterface;
    
    class AcmeAction extends ActionAbstract
    {
        public function getName()
        {
            return 'Acme-Action';
        }
    
        public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
        {
            // @TODO handle request and return response
    
            return $this->response->build(200, [], [
                'message' => $configuration->get('message'),
            ]);
        }
    
        public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
        {
            $builder->add($elementFactory->newInput('message', 'Message', 'text', 'Message of the day'));
        }
    }
