<?php

namespace Citrus\Framework\View;
use Citrus\Core\System\ServiceProviderInterface;
use Citrus\Framework\View\PhpTemplateEngine\PhpTemplateEngine;

class TemplateEngineServiceProvider implements ServiceProviderInterface {
    public function register($app) {
        $app['template_engine'] = function($app) {
            return new PhpTemplateEngine($app['request']);
        };
    }

    public function boot($app) {
        $app['event_dispatcher']->addSubscriber(new TemplateListener($app));
    }

}
