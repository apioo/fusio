
# Action

This folder contains custom action implementations. Each class must implement 
the `Fusio\Engine\ActionInterface` interface and can be used inside the 
`.fusio.yml` deploy configuration. In the following an example implementation:

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
            // @TODO handle request and return response
    
            return $this->response->build(200, [], [
                'message' => 'Hello World!',
            ]);
        }
    }
