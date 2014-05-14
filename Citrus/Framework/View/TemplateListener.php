<?php

namespace Citrus\Framework\View;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class TemplateListener implements EventSubscriberInterface
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW     => 'onKernelView'
        );
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();

        $tr = new TemplateResolver();
        $template = $tr->getTemplate($request);

        $template_engine = $this->container->get('template_engine');
        $template_engine->loadTemplate($this->container->get('dir') . '/views/' . $template);

        $args       = $event->getControllerResult();
        $subcontent = $template_engine->render($args);

        $template_engine->loadTemplate(
            $this->container->get('dir') .
            $this->container['config']['layout']
        );

        $content  = $template_engine->render(Array('_content' => $subcontent));

        $response = new Response($content, 200);

        $event->setResponse($response);
    }
}
