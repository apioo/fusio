<?php
namespace App\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\ContextInterface;

class DocumentationController extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        return 'tokn mety html io ka';
    }
}