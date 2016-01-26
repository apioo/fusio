
# Action

All classes inside this folder which implement the `Fusio\Engine\ActionInterface`
are listed in the action dropdown. In the following a simple example auf a 
custom action.

    <?php

    namespace Fusio\Custom\Action;

    use Fusio\Engine\ActionInterface;
    use Fusio\Engine\ContextInterface;
    use Fusio\Engine\Form\BuilderInterface;
    use Fusio\Engine\Form\ElementFactoryInterface;
    use Fusio\Engine\ParametersInterface;
    use Fusio\Engine\RequestInterface;

    class AcmeAction implements ActionInterface
    {
        /**
         * @Inject
         * @var \Fusio\Engine\Response\FactoryInterface
         */
        protected $response;
        
        public function getName()
        {
            return 'Acme-Action';
        }

        public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
        {
            // @TODO handle request and return response

            return $this->response->build(200, [], [
                'message' => 'Hello World!',
            ]);
        }

        public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
        {
        }
    }
