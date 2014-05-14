<?php

namespace Citrus\Framework\Controller;

use Symfony\Component\HttpKernel\Controller\ControllerResolver as SfControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class ControllerResolver extends SfControllerResolver /*implements ControllerResolverInterface*/
{

    protected $app;

    public function __construct(LoggerInterface $logger = null, $app)
    {
        parent::__construct($logger);
        $this->app = $app;
    }

    protected function createController($controller)
    {
        if (false !== strpos($controller, '::')) {
            list($class, $method) = explode('::', $controller, 2);
            if (!class_exists($class)) {
                throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
            }
            return Array(new $class(), $method);
        }

        if (false === strpos($controller, '/')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($app, $class, $method) = explode('/', $controller, 3);

        $class = implode( '\\', Array(
            'apps',
            $app,
            "controllers",
            ucfirst( $class ) . "Controller"
        ) );

        $method = "do" . ucfirst( $method );

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $inst = new $class();

        if (is_subclass_of($class, "Citrus\Framework\Controller\Controller")) {
            $inst->setContainer($this->app);
        }

        return array($inst, $method);
    }
}
