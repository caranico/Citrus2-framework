<?php

namespace Citrus\Framework\Controller;

use Symfony\Component\HttpFoundation\Request as SfRequest;
use Symfony\Component\HttpFoundation\Response as SfResponse;

class Controller implements ControllerInterface
{

    protected $container;

    /**
     * Generates the view and returns a response
     *
     * @todo set the response content with the view
     * @return SfResponse
     **/
    public function render($args, $view = null, SfResponse $response = null)
    {
        if ($response === null) {
            $response = new SfResponse();
        }
        return $response;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        return $this->container->get($id);
    }
}
